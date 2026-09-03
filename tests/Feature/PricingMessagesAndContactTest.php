<?php

namespace Tests\Feature;

use App\Mail\DealerWelcomeMail;
use App\Models\Admin;
use App\Models\Dealer;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PricingMessagesAndContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_pricing_defaults_and_admin_dealer_updates_are_persisted(): void
    {
        Mail::fake();
        $admin = Admin::firstOrFail();
        $this->assertSame('1000.00', $admin->price);
        $this->assertSame('800.00', $admin->offer_price);
        $details = ['name' => 'Price Dealer', 'email' => 'prices@example.com', 'phone_number' => '9999999999', 'user_limit' => 10];
        $this->actingAs($admin, 'admin')->post(route('admin.dealers.store'), $details)->assertSessionHasNoErrors();
        $dealer = Dealer::where('email', $details['email'])->firstOrFail();
        $this->assertSame('1000.00', $dealer->price);
        $this->assertSame('800.00', $dealer->offer_price);
        Mail::assertSent(DealerWelcomeMail::class);

        $this->post(route('admin.dealers.update', $dealer), $details + [
            'referral_code' => $dealer->referral_code,
            'is_active' => true,
            'subscription_ends_at' => now()->addYear()->toDateString(),
            'price' => 1500.50,
            'offer_price' => 1200.25,
        ])->assertSessionHasNoErrors();
        $this->getJson(route('admin.dealers.show', $dealer))->assertOk()
            ->assertJsonPath('price', '1500.50')->assertJsonPath('offer_price', '1200.25');

        $this->post(route('admin.profile.update'), [
            'name' => $admin->name, 'email' => $admin->email, 'price' => 2000, 'offer_price' => 1800,
        ])->assertSessionHasNoErrors();
        $this->assertSame('2000.00', $admin->fresh()->price);
        $this->assertSame('1800.00', $admin->fresh()->offer_price);

        $this->actingAs($dealer, 'dealer')->post(route('dealer.profile.update'), $details + [
            'referral_code' => $dealer->referral_code, 'price' => 999.99, 'offer_price' => 0,
        ])->assertSessionHasNoErrors();
        $this->assertSame('999.99', $dealer->fresh()->price);
        $this->assertSame('0.00', $dealer->fresh()->offer_price);
        $this->post(route('dealer.profile.update'), $details + [
            'referral_code' => $dealer->referral_code, 'price' => -1, 'offer_price' => 'invalid',
        ])->assertSessionHasErrors(['price', 'offer_price']);

        $dealer->update(['subscription_ends_at' => now()->subDay()]);
        $this->get(route('dealer.dashboard'))->assertForbidden()->assertSee('Offer Price')->assertSee('999.99')->assertSee('0.00');
    }

    public function test_profile_api_includes_prices_for_dealer_and_admin_and_expired_login(): void
    {
        $dealer = $this->dealer();
        $user = User::factory()->create(['dealer_id' => $dealer->id]);
        $this->actingAs($user, 'sanctum')->getJson('/api/user')->assertOk()
            ->assertJsonPath('data.dealer.price', '1000.00')
            ->assertJsonPath('data.dealer.offer_price', '800.00')
            ->assertJsonPath('data.admin_contact.price', '1000.00');
        $user->update(['dealer_id' => null]);
        $this->getJson('/api/user')->assertOk()
            ->assertJsonPath('data.admin.price', '1000.00')->assertJsonPath('data.admin.offer_price', '800.00');
        $dealer->update(['subscription_ends_at' => now()->subDay(), 'price' => 1500, 'offer_price' => 1200]);
        $user->update(['dealer_id' => $dealer->id]);
        $this->postJson('/api/auth/send-otp', ['email' => $user->email])->assertForbidden()
            ->assertJsonPath('data.dealer_subscription.price', '1500.00')
            ->assertJsonPath('data.dealer_subscription.offer_price', '1200.00');
    }

    public function test_setup_and_profile_updates_return_and_preserve_contact_details(): void
    {
        $user = User::factory()->create(['name' => '', 'email_verified_at' => now()]);
        $response = $this->postJson('/api/auth/complete-setup', [
            'email' => $user->email, 'name' => 'Contact User',
            'whatsapp_number' => '+919876543210', 'address' => 'Pune, Maharashtra',
        ])->assertOk()->assertJsonPath('data.user.whatsapp_number', '+919876543210')
            ->assertJsonPath('data.user.address', 'Pune, Maharashtra');
        $token = $response->json('data.access_token');
        $this->withToken($token)->getJson('/api/user')->assertOk()->assertJsonPath('data.user.address', 'Pune, Maharashtra');
        $this->withToken($token)->postJson('/api/user/update-profile', ['address' => 'Mumbai'])->assertOk()
            ->assertJsonPath('data.user.address', 'Mumbai')->assertJsonPath('data.user.whatsapp_number', '+919876543210');
        $this->withToken($token)->postJson('/api/user/update-profile', ['whatsapp_number' => null])->assertOk()
            ->assertJsonPath('data.user.whatsapp_number', null);
        $this->withToken($token)->postJson('/api/user/update-profile', ['whatsapp_number' => str_repeat('1', 31), 'address' => ['invalid']])
            ->assertUnprocessable()->assertJsonValidationErrors(['whatsapp_number', 'address']);
    }

    public function test_admin_can_manage_messages_and_validation_preserves_existing_content(): void
    {
        $this->actingAs(Admin::firstOrFail(), 'admin');
        $this->get(route('admin.messages.index'))->assertOk()->assertSee('Manage Messages')->assertSee('No messages yet.');
        $this->post(route('admin.messages.store'), ['title' => 'Welcome', 'message' => '<script>alert(1)</script>', 'status' => 1])->assertSessionHasNoErrors();
        $message = Message::firstOrFail();
        $this->get(route('admin.messages.index', ['edit' => $message->id]))->assertOk()->assertSee('Edit Message')
            ->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
        $this->post(route('admin.messages.update', $message), ['title' => 'Updated', 'message' => 'New body', 'status' => 0])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('messages', ['id' => $message->id, 'title' => 'Updated', 'status' => 0]);
        $this->post(route('admin.messages.update', $message), ['title' => '', 'message' => '', 'status' => 2])->assertSessionHasErrors(['title', 'message', 'status']);
        $this->assertSame('New body', $message->fresh()->message);
        $this->delete(route('admin.messages.destroy', $message))->assertRedirect(route('admin.messages.index'));
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    public function test_messages_api_paginates_only_active_messages_with_total_and_enforces_access(): void
    {
        $this->getJson('/api/messages')->assertUnauthorized();
        $this->get(route('admin.messages.index'))->assertRedirect(route('admin.login'));
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')->getJson('/api/messages')->assertOk()
            ->assertJsonPath('data.total', 0)->assertJsonPath('data.messages', []);
        Message::create(['title' => 'First', 'message' => 'First body', 'status' => true]);
        $latest = Message::create(['title' => 'Latest', 'message' => 'Latest body', 'status' => true]);
        Message::create(['title' => 'Hidden', 'message' => 'Hidden body', 'status' => false]);
        $this->getJson('/api/messages?per_page=1')->assertOk()->assertJsonPath('data.total', 2)
            ->assertJsonCount(1, 'data.messages')->assertJsonPath('data.messages.0.id', $latest->id)
            ->assertJsonPath('data.last_page', 2)->assertDontSee('Hidden body');
        $this->getJson('/api/messages?page=2&per_page=1')->assertOk()->assertJsonPath('data.messages.0.title', 'First');
        $this->getJson('/api/messages?per_page=101')->assertUnprocessable();
        $this->getJson('/api/messages?page=0')->assertUnprocessable();
        $user->update(['approval_status' => 'disapproved']);
        $this->getJson('/api/messages')->assertForbidden();
        $this->actingAs($this->dealer(), 'dealer')->post(route('admin.messages.store'), ['title' => 'Denied', 'message' => 'Denied', 'status' => 1])
            ->assertRedirect(route('admin.login'));
        $this->assertDatabaseMissing('messages', ['title' => 'Denied']);
    }

    private function dealer(): Dealer
    {
        return Dealer::create([
            'name' => 'Test Dealer', 'email' => 'dealer@example.com', 'phone_number' => '9999999999',
            'password' => 'Password@123', 'login_password' => 'Password@123', 'referral_code' => 'PRICE123', 'user_limit' => 10,
            'is_active' => true, 'subscription_ends_at' => now()->addYear(),
        ]);
    }
}
