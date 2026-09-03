<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Dealer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FullUserProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_dealer_can_view_and_edit_complete_profiles_and_images(): void
    {
        Storage::fake('public');
        $dealer = $this->dealer();
        $user = User::factory()->create([
            'dealer_id' => $dealer->id,
            'subscription_started_at' => now()->subDays(4),
            'subscription_ends_at' => now()->subMinute(),
            'approval_status' => 'pending',
        ]);

        foreach (['admin', 'dealer'] as $guard) {
            $this->actingAs($guard === 'admin' ? Admin::firstOrFail() : $dealer, $guard);
            $data = $this->details($user) + [
                'whatsapp_number' => '+919876543210',
                'address' => "First floor\nPune, Maharashtra",
                'destination' => 'Business Advisor',
                'language' => 'mr',
                'password' => 'UpdatedPassword@123',
                'profile_photo' => $this->image('photo.png'),
                'logo' => $this->image('logo.png'),
            ];
            $this->post(route($guard.'.users.update', $user), $data)->assertSessionHasNoErrors();
            $user->refresh();
            $this->assertSame($data['address'], $user->address);
            $this->assertSame('mr', $user->language);
            $this->assertTrue(Hash::check('UpdatedPassword@123', $user->password));
            Storage::disk('public')->assertExists(ltrim(str_replace('/storage/', '', $user->profile_photo), '/'));
            Storage::disk('public')->assertExists(ltrim(str_replace('/storage/', '', $user->logo), '/'));
            $this->getJson(route($guard.'.users.show', $user))->assertOk()
                ->assertJsonPath('whatsapp_number', '+919876543210')
                ->assertJsonPath('address', $data['address'])
                ->assertJsonPath('destination', 'Business Advisor')
                ->assertJsonPath('language', 'mr')
                ->assertJsonPath('dealer.id', $dealer->id)
                ->assertJsonMissingPath('password')->assertJsonMissingPath('otp');

            // Omitted optional fields and blank password preserve existing values.
            $password = $user->password;
            $this->post(route($guard.'.users.update', $user), $this->details($user) + ['password' => ''])
                ->assertSessionHasNoErrors();
            $this->assertSame($data['address'], $user->fresh()->address);
            $this->assertSame($password, $user->fresh()->password);
            $this->post(route($guard.'.users.update', $user), $this->details($user) + [
                'remove_profile_photo' => 1, 'remove_logo' => 1, 'address' => null, 'whatsapp_number' => null,
            ])->assertSessionHasNoErrors();
            $user->refresh();
            $this->assertNull($user->profile_photo);
            $this->assertNull($user->logo);
            $this->assertNull($user->address);
            $this->assertNull($user->whatsapp_number);
        }
    }

    public function test_profile_validation_and_dealer_ownership_are_enforced(): void
    {
        $dealer = $this->dealer();
        $user = User::factory()->create(['dealer_id' => $dealer->id, 'approval_status' => 'pending', 'subscription_ends_at' => now()->addDay()]);
        $other = User::factory()->create(['dealer_id' => null]);
        $this->actingAs($dealer, 'dealer');
        $this->getJson(route('dealer.users.show', $other))->assertNotFound();
        $this->postJson(route('dealer.users.update', $other), $this->details($other) + ['address' => 'Changed'])->assertNotFound();
        $this->assertNull($other->fresh()->address);

        foreach (['admin', 'dealer'] as $guard) {
            $this->actingAs($guard === 'admin' ? Admin::firstOrFail() : $dealer, $guard);
            $this->postJson(route($guard.'.users.update', $user), $this->details($user) + [
                'language' => 'invalid', 'whatsapp_number' => str_repeat('1', 31), 'address' => ['invalid'],
                'profile_photo' => UploadedFile::fake()->create('bad.txt', 1, 'text/plain'),
            ])->assertUnprocessable()->assertJsonValidationErrors(['language', 'whatsapp_number', 'address', 'profile_photo']);
            $this->postJson(route($guard.'.users.update', $user), $this->details($user) + ['approval_status' => 'approved'])
                ->assertUnprocessable()->assertJsonValidationErrors(['approval_status']);
        }

        $user->update(['subscription_ends_at' => now()->subMinute()]);
        $this->post(route('dealer.users.update', $user), $this->details($user) + ['approval_status' => 'approved', 'dealer_id' => null])
            ->assertSessionHasNoErrors();
        $this->assertSame('approved', $user->fresh()->approval_status);
        $this->assertSame($dealer->id, $user->fresh()->dealer_id);
    }

    public function test_all_user_management_screens_include_full_profile_controls(): void
    {
        $dealer = $this->dealer();
        $this->actingAs(Admin::firstOrFail(), 'admin');
        foreach ([route('admin.users.index'), route('admin.dealers.users', $dealer)] as $url) {
            $this->get($url)->assertOk()->assertSee('name="whatsapp_number"', false)
                ->assertSee('name="address"', false)->assertSee('name="language"', false)
                ->assertSee('name="approval_status"', false)->assertSee('name="profile_photo"', false)
                ->assertSee('name="logo"', false)->assertSee('User Account Details')->assertSee('Last Updated');
        }
        $this->actingAs($dealer, 'dealer')->get(route('dealer.users.index'))->assertOk()
            ->assertSee('enctype="multipart/form-data"', false)->assertSee('name="whatsapp_number"', false)
            ->assertSee('name="address"', false)->assertSee('name="language"', false)
            ->assertSee('name="profile_photo"', false)->assertSee('name="logo"', false);
        $dealer->update(['subscription_ends_at' => now()->subDay()]);
        $this->get(route('dealer.dashboard'))->assertForbidden()
            ->assertSee('<s style="text-decoration-thickness:2px">1,000.00</s>', false)
            ->assertSee('<strong>800.00</strong>', false);
    }

    private function details(User $user): array
    {
        return [
            'name' => $user->name, 'email' => $user->email, 'phone_number' => '9876543210',
            'subscription_started_at' => now()->subDays(4)->toDateTimeString(),
            'subscription_ends_at' => now()->addMonth()->toDateTimeString(),
        ];
    }

    private function dealer(): Dealer
    {
        return Dealer::create([
            'name' => 'Profile Dealer', 'email' => 'profile-dealer@example.com', 'phone_number' => '9999999999',
            'password' => 'Password@123', 'login_password' => 'Password@123', 'referral_code' => 'PROFILE1',
            'user_limit' => 10, 'is_active' => true, 'subscription_ends_at' => now()->addYear(),
        ]);
    }

    private function image(string $name): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl6q9sAAAAASUVORK5CYII='));
    }
}
