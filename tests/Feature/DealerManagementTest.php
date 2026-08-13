<?php

namespace Tests\Feature;

use App\Mail\DealerWelcomeMail;
use App\Mail\UserWelcomeMail;
use App\Models\Admin;
use App\Models\Dealer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DealerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_dealer_with_generated_credentials_and_referral_code(): void
    {
        Mail::fake();
        $admin = Admin::firstOrFail();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.dealers.store'), [
            'name' => 'Darshan Kondekar',
            'phone_number' => '9876543210',
            'alternative_phone_number' => '9123456780',
            'email' => 'dealer@example.com',
            'user_limit' => 10,
        ]);

        $response->assertRedirect(route('admin.dealers.index'));
        $dealer = Dealer::where('email', 'dealer@example.com')->firstOrFail();
        $this->assertMatchesRegularExpression('/^[A-Z0-9]{8}$/', $dealer->referral_code);
        $this->assertTrue(Hash::check('DarshanKondekar@123', $dealer->password));
        $this->assertSame('DarshanKondekar@123', $dealer->login_password);
        Mail::assertSent(DealerWelcomeMail::class, fn ($mail) => $mail->hasTo('dealer@example.com') && $mail->plainPassword === 'DarshanKondekar@123'
        );
    }

    public function test_dealer_login_works_and_inactive_dealer_cannot_login(): void
    {
        $dealer = $this->dealer(['password' => 'DealerTest@123']);

        $this->post(route('dealer.login.submit'), [
            'email' => $dealer->email,
            'password' => 'DealerTest@123',
        ])->assertRedirect(route('dealer.dashboard'));

        $this->post(route('dealer.logout'));
        $dealer->update(['is_active' => false]);
        $this->post(route('dealer.login.submit'), [
            'email' => $dealer->email,
            'password' => 'DealerTest@123',
        ])->assertSessionHasErrors('email');
    }

    public function test_dealer_login_page_displays_the_advisorx_logo(): void
    {
        $this->get(route('dealer.login'))
            ->assertOk()
            ->assertSee(asset('images/advisorx-pro-logo.jpg'))
            ->assertSee('alt="AdvisorX Pro logo"', false);
    }

    public function test_dealer_cannot_create_more_users_than_assigned_limit(): void
    {
        Mail::fake();
        $dealer = $this->dealer(['user_limit' => 1]);

        $this->actingAs($dealer, 'dealer')->post(route('dealer.users.store'), [
            'name' => 'First User', 'email' => 'first@example.com',
        ])->assertSessionHasNoErrors();

        $this->actingAs($dealer, 'dealer')->post(route('dealer.users.store'), [
            'name' => 'Second User', 'email' => 'second@example.com',
        ])->assertSessionHasErrors('user_limit');

        $this->assertSame(1, $dealer->users()->count());
        $user = $dealer->users()->firstOrFail();
        $this->assertTrue($user->subscription_started_at->isSameSecond($user->subscription_ends_at->copy()->subDays(4)));
        Mail::assertSent(UserWelcomeMail::class, fn ($mail) => $mail->hasTo('first@example.com'));
    }

    public function test_admin_user_list_excludes_dealer_users_and_dealer_drilldown_shows_them(): void
    {
        $admin = Admin::firstOrFail();
        $dealer = $this->dealer();
        User::factory()->create(['name' => 'Direct Admin User', 'email' => 'direct@example.com']);
        $this->userFor($dealer, now()->addDays(4), 'dealer-owned@example.com');

        $this->actingAs($admin, 'admin')->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Direct Admin User')
            ->assertDontSee('dealer-owned@example.com');

        $this->actingAs($admin, 'admin')->get(route('admin.dealers.users', $dealer))
            ->assertOk()
            ->assertSee('dealer-owned@example.com')
            ->assertDontSee('Direct Admin User');
    }

    public function test_dealer_can_only_manage_owned_users_and_approve_after_trial_expiry(): void
    {
        $dealer = $this->dealer();
        $otherDealer = $this->dealer(['email' => 'other@example.com', 'referral_code' => 'OTH3R123']);
        $activeTrial = $this->userFor($dealer, now()->addDay());
        $expiredTrial = $this->userFor($dealer, now()->subMinute(), 'expired@example.com');
        $otherUser = $this->userFor($otherDealer, now()->subMinute(), 'other-user@example.com');

        $this->actingAs($dealer, 'dealer')->post(route('dealer.users.approval', $activeTrial), [
            'approval_status' => 'approved',
        ])->assertSessionHasErrors('approval_status');

        $this->actingAs($dealer, 'dealer')->post(route('dealer.users.approval', $expiredTrial), [
            'approval_status' => 'approved',
        ])->assertSessionHasNoErrors();
        $this->assertSame('approved', $expiredTrial->fresh()->approval_status);

        $this->actingAs($dealer, 'dealer')->get(route('dealer.users.show', $otherUser))->assertNotFound();
    }

    public function test_expired_pending_user_is_denied_api_login_but_approved_user_has_access(): void
    {
        $dealer = $this->dealer();
        $user = $this->userFor($dealer, now()->subMinute());
        $user->update(['otp' => '1234', 'otp_expires_at' => now()->addMinutes(5)]);

        $this->postJson('/api/auth/verify-otp', ['email' => $user->email, 'otp' => '1234'])
            ->assertForbidden();

        $user->update(['approval_status' => 'approved']);
        $this->postJson('/api/auth/verify-otp', ['email' => $user->email, 'otp' => '1234'])
            ->assertOk();
    }

    public function test_existing_api_token_stops_working_when_trial_expires(): void
    {
        $dealer = $this->dealer();
        $user = $this->userFor($dealer, now()->subMinute());
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->getJson('/api/user')->assertForbidden();

        $user->update(['approval_status' => 'approved']);
        $this->withToken($token)->getJson('/api/user')->assertOk();
    }

    private function dealer(array $attributes = []): Dealer
    {
        return Dealer::create(array_merge([
            'name' => 'Test Dealer',
            'phone_number' => '9999999999',
            'email' => 'dealer@test.com',
            'password' => 'DealerTest@123',
            'login_password' => $attributes['password'] ?? 'DealerTest@123',
            'user_limit' => 10,
            'referral_code' => 'TEST1234',
            'is_active' => true,
        ], $attributes));
    }

    private function userFor(Dealer $dealer, $trialEndsAt, string $email = 'user@example.com'): User
    {
        return User::create([
            'dealer_id' => $dealer->id,
            'name' => 'Test User',
            'email' => $email,
            'password' => 'password123',
            'subscription_started_at' => now()->subDays(4),
            'subscription_ends_at' => $trialEndsAt,
            'approval_status' => 'pending',
        ]);
    }
}
