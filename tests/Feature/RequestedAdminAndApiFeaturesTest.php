<?php

namespace Tests\Feature;

use App\Mail\UserWelcomeMail;
use App\Models\Admin;
use App\Models\Dealer;
use App\Models\Subtool;
use App\Models\Tool;
use App\Models\ToolMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RequestedAdminAndApiFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_otp_assigns_new_user_to_referred_dealer_with_four_day_trial(): void
    {
        Mail::fake();
        $dealer = $this->dealer();

        $this->postJson('/api/auth/send-otp', [
            'email' => 'referred@example.com',
            'refer_code' => strtolower($dealer->referral_code),
        ])->assertOk();

        $user = User::where('email', 'referred@example.com')->firstOrFail();
        $this->assertSame($dealer->id, $user->dealer_id);
        $this->assertSame('pending', $user->approval_status);
        $this->assertTrue($user->subscription_started_at->copy()->addDays(4)->isSameSecond($user->subscription_ends_at));
    }

    public function test_send_otp_assigns_user_to_admin_and_rejects_disapproved_user(): void
    {
        Mail::fake();

        $this->postJson('/api/auth/send-otp', ['email' => 'admin-owned@example.com'])->assertOk();
        $user = User::where('email', 'admin-owned@example.com')->firstOrFail();
        $this->assertNull($user->dealer_id);

        $user->update(['approval_status' => 'disapproved']);
        $this->postJson('/api/auth/send-otp', ['email' => $user->email])
            ->assertForbidden()
            ->assertJsonPath('data.is_expired', 0);
    }

    public function test_verify_otp_and_user_endpoint_return_subscription_flag_and_owner(): void
    {
        $dealer = $this->dealer();
        $user = User::create([
            'dealer_id' => $dealer->id,
            'name' => 'API User',
            'email' => 'api-user@example.com',
            'password' => 'password',
            'otp' => '1234',
            'otp_expires_at' => now()->addMinutes(5),
            'subscription_started_at' => now(),
            'subscription_ends_at' => now()->addDays(4),
            'approval_status' => 'pending',
        ]);

        $response = $this->postJson('/api/auth/verify-otp', ['email' => $user->email, 'otp' => '1234'])
            ->assertOk()
            ->assertJsonPath('data.is_expired', 1)
            ->assertJsonPath('data.dealer', null);

        $token = $response->json('data.access_token');
        $this->withToken($token)->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('data.dealer.id', $dealer->id)
            ->assertJsonPath('data.admin', null);

        $adminUser = User::factory()->create([
            'dealer_id' => null,
            'subscription_started_at' => now(),
            'subscription_ends_at' => now()->addDays(4),
            'approval_status' => 'pending',
        ]);
        $adminToken = $adminUser->createToken('test')->plainTextToken;
        $this->app['auth']->forgetGuards();
        $this->withToken($adminToken)->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('data.dealer', null)
            ->assertJsonPath('data.admin.id', Admin::firstOrFail()->id);
    }

    public function test_admin_can_update_contact_fields_subtool_and_media(): void
    {
        $admin = Admin::firstOrFail();
        $this->actingAs($admin, 'admin')->post(route('admin.profile.update'), [
            'name' => $admin->name,
            'email' => $admin->email,
            'phone_number' => '9876543210',
            'alternative_phone_number' => '9123456780',
        ])->assertSessionHasNoErrors();
        $this->assertSame('9876543210', $admin->fresh()->phone_number);

        $tool = Tool::create(['title' => 'Tool', 'icon' => 'tool', 'status' => 1]);
        $subtool = Subtool::create(['tool_id' => $tool->id, 'title' => 'Old', 'status' => 1]);
        $media = ToolMedia::create([
            'tool_id' => $tool->id,
            'subtool_id' => $subtool->id,
            'title' => 'Old Media',
            'file_path' => 'uploads/tools/example.jpg',
            'media_type' => 'image',
            'language' => 'en',
            'status' => 1,
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.tools.subtools.update', $subtool), [
            'title' => 'Updated Subtool', 'description' => 'Updated', 'status' => 0,
        ])->assertSessionHasNoErrors();
        $this->assertSame('Updated Subtool', $subtool->fresh()->title);

        $this->actingAs($admin, 'admin')->post(route('admin.tools.media.update', $media), [
            'title' => 'Updated Media', 'language' => 'hi', 'description' => 'Updated', 'status' => 0,
        ])->assertSessionHasNoErrors();
        $this->assertSame('Updated Media', $media->fresh()->title);
        $this->assertSame('hi', $media->fresh()->language);

        $rootMedia = ToolMedia::create([
            'tool_id' => $tool->id,
            'subtool_id' => null,
            'title' => 'Root Media',
            'file_path' => 'uploads/tools/root.jpg',
            'media_type' => 'image',
            'language' => 'en',
            'status' => 1,
        ]);
        $this->actingAs($admin, 'admin')->post(route('admin.tools.media.update', $rootMedia), [
            'title' => 'Updated Root Media', 'language' => 'mr', 'description' => 'Root updated', 'status' => 1,
        ])->assertSessionHasNoErrors();
        $this->assertSame('Updated Root Media', $rootMedia->fresh()->title);
        $this->actingAs($admin, 'admin')->get(route('admin.tools.manage', $tool))
            ->assertOk()
            ->assertSee('Edit root media');
    }

    public function test_admin_can_approve_or_disapprove_during_active_trial_and_listing_shows_dates(): void
    {
        $admin = Admin::firstOrFail();
        $user = User::factory()->create([
            'subscription_started_at' => now(),
            'subscription_ends_at' => now()->addDays(4),
            'approval_status' => 'pending',
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.users.approval', $user), [
            'approval_status' => 'disapproved',
        ])->assertSessionHasNoErrors();
        $this->assertSame('disapproved', $user->fresh()->approval_status);

        $this->actingAs($admin, 'admin')->post(route('admin.users.approval', $user), [
            'approval_status' => 'approved',
        ])->assertSessionHasNoErrors();
        $this->assertSame('approved', $user->fresh()->approval_status);

        $this->actingAs($admin, 'admin')->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Created At')
            ->assertSee('Expired At')
            ->assertSee('Approve user')
            ->assertSee('Disapprove user');
    }

    public function test_admin_can_create_multiple_users_without_a_user_limit(): void
    {
        Mail::fake();
        $admin = Admin::firstOrFail();

        foreach (range(1, 3) as $number) {
            $this->actingAs($admin, 'admin')->post(route('admin.users.store'), [
                'name' => 'Admin User '.$number,
                'email' => "admin-user-{$number}@example.com",
            ])->assertSessionHasNoErrors();
        }

        $this->assertSame(3, User::whereNull('dealer_id')->count());
        Mail::assertSent(UserWelcomeMail::class, 3);
    }

    private function dealer(): Dealer
    {
        return Dealer::create([
            'name' => 'Referral Dealer',
            'phone_number' => '9999999999',
            'email' => 'referral-dealer@example.com',
            'password' => 'password',
            'login_password' => 'password',
            'user_limit' => 10,
            'referral_code' => 'REFER123',
            'is_active' => true,
        ]);
    }
}
