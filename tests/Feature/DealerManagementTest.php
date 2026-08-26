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

    public function test_duplicate_dealer_email_error_is_displayed_in_the_create_popup(): void
    {
        $admin = Admin::firstOrFail();
        $this->dealer(['email' => 'existing@example.com']);

        $response = $this->actingAs($admin, 'admin')->from(route('admin.dealers.index'))->post(route('admin.dealers.store'), [
            'name' => 'Duplicate Dealer',
            'phone_number' => '9876543210',
            'email' => 'existing@example.com',
            'user_limit' => 10,
        ]);

        $response->assertRedirect(route('admin.dealers.index'))
            ->assertSessionHasErrors(['email' => 'A dealer with this email address already exists.']);

        $this->actingAs($admin, 'admin')->get(route('admin.dealers.index'))
            ->assertSeeInOrder([
                'name="email"',
                'A dealer with this email address already exists.',
            ], false)
            ->assertDontSee('Dealer could not be saved');
    }

    public function test_admin_and_dealer_can_update_user_subscription_dates(): void
    {
        $admin = Admin::firstOrFail();
        $adminUser = User::factory()->create([
            'dealer_id' => null,
            'subscription_started_at' => now()->subDay(),
            'subscription_ends_at' => now()->addDays(3),
        ]);

        $this->actingAs($admin, 'admin')->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('type="datetime-local" name="subscription_started_at"', false)
            ->assertSee('type="datetime-local" name="subscription_ends_at"', false)
            ->assertSee('oneYearAfterDateTimeLocal(user.created_at)', false);

        $this->actingAs($admin, 'admin')->post(route('admin.users.update', $adminUser), [
            'name' => $adminUser->name,
            'email' => $adminUser->email,
            'subscription_started_at' => '2026-08-10 09:30:00',
            'subscription_ends_at' => '2026-08-20 18:45:00',
        ])->assertSessionHasNoErrors();

        $this->assertSame('2026-08-10 09:30:00', $adminUser->fresh()->subscription_started_at->format('Y-m-d H:i:s'));
        $this->assertSame('2026-08-20 18:45:00', $adminUser->fresh()->subscription_ends_at->format('Y-m-d H:i:s'));

        $adminMaximum = $adminUser->created_at->copy()->addYear();
        $this->actingAs($admin, 'admin')->post(route('admin.users.update', $adminUser), [
            'name' => $adminUser->name,
            'email' => $adminUser->email,
            'subscription_started_at' => $adminMaximum->copy()->subDay()->format('Y-m-d H:i:s'),
            'subscription_ends_at' => $adminMaximum->copy()->addMinute()->format('Y-m-d H:i:s'),
        ])->assertSessionHasErrors('subscription_ends_at');

        $dealer = $this->dealer();
        $dealerUser = $this->userFor($dealer, now()->addDays(2), 'subscription@example.com');
        $this->actingAs($dealer, 'dealer')->get(route('dealer.users.index'))
            ->assertOk()
            ->assertSee('type="datetime-local" name="subscription_started_at"', false)
            ->assertSee('type="datetime-local" name="subscription_ends_at"', false)
            ->assertSee('oneYearAfterDateTimeLocal(user.created_at)', false);

        $this->actingAs($dealer, 'dealer')->post(route('dealer.users.update', $dealerUser), [
            'name' => $dealerUser->name,
            'email' => $dealerUser->email,
            'subscription_started_at' => '2026-08-11 08:00:00',
            'subscription_ends_at' => '2026-08-25 17:15:00',
        ])->assertSessionHasNoErrors();

        $this->assertSame('2026-08-11 08:00:00', $dealerUser->fresh()->subscription_started_at->format('Y-m-d H:i:s'));
        $this->assertSame('2026-08-25 17:15:00', $dealerUser->fresh()->subscription_ends_at->format('Y-m-d H:i:s'));

        $dealerMaximum = $dealerUser->created_at->copy()->addYear();
        $this->actingAs($dealer, 'dealer')->post(route('dealer.users.update', $dealerUser), [
            'name' => $dealerUser->name,
            'email' => $dealerUser->email,
            'subscription_started_at' => $dealerMaximum->copy()->subDay()->format('Y-m-d H:i:s'),
            'subscription_ends_at' => $dealerMaximum->copy()->addMinute()->format('Y-m-d H:i:s'),
        ])->assertSessionHasErrors('subscription_ends_at');
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

    public function test_user_profile_endpoint_ignores_subscription_and_approval_status(): void
    {
        $dealer = $this->dealer();
        $user = $this->userFor($dealer, now()->subMinute());
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonPath('data.dealer.id', $dealer->id)
            ->assertJsonPath('data.admin', null);

        $user->update(['approval_status' => 'disapproved']);
        $this->withToken($token)->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('data.user.approval_status', 'disapproved')
            ->assertJsonPath('data.dealer.id', $dealer->id);
    }

    public function test_new_dealer_receives_a_one_year_free_subscription_and_sees_it_in_profile(): void
    {
        Mail::fake();
        $admin = Admin::firstOrFail();

        $this->actingAs($admin, 'admin')->post(route('admin.dealers.store'), [
            'name' => 'Annual Dealer',
            'phone_number' => '9876543210',
            'email' => 'annual@example.com',
            'user_limit' => 3,
        ])->assertSessionHasNoErrors();

        $dealer = Dealer::where('email', 'annual@example.com')->firstOrFail();
        $this->assertNotNull($dealer->subscription_started_at);
        $this->assertTrue($dealer->subscription_started_at->copy()->addYear()->isSameSecond($dealer->subscription_ends_at));
        $this->assertSame('active', $dealer->subscriptionStatus());

        $this->actingAs($dealer, 'dealer')->get(route('dealer.dashboard'))
            ->assertOk()
            ->assertSee('Free subscription')
            ->assertSee($dealer->subscription_ends_at->format('d M Y'));

        $this->actingAs($admin, 'admin')->get(route('admin.dealers.index'))
            ->assertOk()
            ->assertSee('id="dealer-subscription-start"', false)
            ->assertSee('id="dealer-subscription-end"', false)
            ->assertSee('#dealer-subscription-end::-webkit-calendar-picker-indicator', false);
    }

    public function test_expired_dealer_sees_subscription_popup_with_admin_numbers_and_cannot_use_portal(): void
    {
        $admin = Admin::firstOrFail();
        $admin->update([
            'phone_number' => '9876543210',
            'alternative_phone_number' => '9123456780',
        ]);
        $dealer = $this->dealer([
            'subscription_started_at' => now()->subYear(),
            'subscription_ends_at' => now()->subMinute(),
        ]);

        $this->post(route('dealer.login.submit'), [
            'email' => $dealer->email,
            'password' => 'DealerTest@123',
        ])->assertRedirect(route('dealer.dashboard'));

        $this->get(route('dealer.dashboard'))
            ->assertForbidden()
            ->assertSee('Subscription Required')
            ->assertSee('9876543210')
            ->assertSee('9123456780');

        $this->get(route('dealer.users.index'))->assertForbidden();
    }

    public function test_expired_dealer_blocks_its_users_from_login_and_all_authenticated_functionality(): void
    {
        $admin = Admin::firstOrFail();
        $admin->update(['phone_number' => '9988776655']);
        $dealer = $this->dealer([
            'subscription_started_at' => now()->subYear(),
            'subscription_ends_at' => now()->subMinute(),
        ]);
        $user = $this->userFor($dealer, now()->addDays(2));
        $user->update([
            'approval_status' => 'approved',
            'otp' => '1234',
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        $this->postJson('/api/auth/verify-otp', ['email' => $user->email, 'otp' => '1234'])
            ->assertForbidden()
            ->assertJsonPath('data.subscription_required', true)
            ->assertJsonPath('data.dealer_subscription_expired', true)
            ->assertJsonPath('data.admin_mobile_numbers.0', '9988776655');

        $token = $user->createToken('existing-login')->plainTextToken;
        $this->withToken($token)->getJson('/api/tools')
            ->assertForbidden()
            ->assertJsonPath('data.dealer_subscription_expired', true);
    }

    public function test_admin_extension_restores_dealer_and_dealer_user_access_for_the_selected_period(): void
    {
        $admin = Admin::firstOrFail();
        $dealer = $this->dealer([
            'subscription_started_at' => now()->subYear(),
            'subscription_ends_at' => now()->subDay(),
        ]);
        $user = $this->userFor($dealer, now()->addDays(2));
        $user->update(['otp' => '1234', 'otp_expires_at' => now()->addMinutes(5)]);
        $newEndDate = now()->addMonth()->format('Y-m-d');

        $this->actingAs($admin, 'admin')->post(route('admin.dealers.update', $dealer), [
            'name' => $dealer->name,
            'phone_number' => $dealer->phone_number,
            'alternative_phone_number' => $dealer->alternative_phone_number,
            'email' => $dealer->email,
            'user_limit' => $dealer->user_limit,
            'referral_code' => $dealer->referral_code,
            'is_active' => true,
            'subscription_ends_at' => $newEndDate,
        ])->assertSessionHasNoErrors();

        $this->assertSame($newEndDate.' 23:59:59', $dealer->fresh()->subscription_ends_at->format('Y-m-d H:i:s'));
        $this->post(route('dealer.login.submit'), [
            'email' => $dealer->email,
            'password' => 'DealerTest@123',
        ])->assertRedirect(route('dealer.dashboard'));
        $this->get(route('dealer.dashboard'))->assertOk();

        $this->postJson('/api/auth/verify-otp', ['email' => $user->email, 'otp' => '1234'])
            ->assertOk()
            ->assertJsonPath('data.is_expired', 1);
    }

    public function test_admin_can_change_dealer_end_date_to_any_selected_date(): void
    {
        $admin = Admin::firstOrFail();
        $dealer = $this->dealer([
            'subscription_started_at' => '2026-08-26 10:00:00',
            'subscription_ends_at' => '2027-08-31 23:59:59',
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.dealers.update', $dealer), [
            'name' => $dealer->name,
            'phone_number' => $dealer->phone_number,
            'alternative_phone_number' => $dealer->alternative_phone_number,
            'email' => $dealer->email,
            'user_limit' => $dealer->user_limit,
            'referral_code' => $dealer->referral_code,
            'is_active' => true,
            'subscription_ends_at' => '2027-08-22',
        ])->assertSessionHasNoErrors();

        $this->assertSame('2027-08-22 23:59:59', $dealer->fresh()->subscription_ends_at->format('Y-m-d H:i:s'));
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
