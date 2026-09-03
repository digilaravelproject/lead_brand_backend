<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DealerWelcomeMail;
use App\Models\Dealer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DealerController extends Controller
{
    public function index()
    {
        $dealers = Dealer::withCount('users')->latest()->paginate(10);

        return view('admin.dealers.index', compact('dealers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:30'],
            'alternative_phone_number' => ['nullable', 'string', 'max:30'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'offer_price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'email' => ['required', 'email', 'max:255', 'unique:dealers,email'],
            'user_limit' => ['required', 'integer', 'min:0'],
        ], [
            'email.unique' => 'A dealer with this email address already exists.',
        ]);

        $plainPassword = $this->makePassword($validated['name']);
        $subscriptionStartedAt = now();

        $dealer = DB::transaction(function () use ($validated, $plainPassword, $subscriptionStartedAt) {
            $dealer = Dealer::create($validated + [
                'password' => $plainPassword,
                'login_password' => $plainPassword,
                'referral_code' => $this->uniqueReferralCode(),
                'is_active' => true,
                'subscription_started_at' => $subscriptionStartedAt,
                'subscription_ends_at' => $subscriptionStartedAt->copy()->addYear(),
            ]);

            Mail::to($dealer->email)->send(new DealerWelcomeMail($dealer, $plainPassword));

            return $dealer;
        });

        return redirect()->route('admin.dealers.index')
            ->with('success', "Dealer {$dealer->name} created and login details emailed.");
    }

    public function show(int $id)
    {
        $dealer = Dealer::withCount('users')->findOrFail($id);

        return response()->json([
            ...$dealer->toArray(),
            'login_password' => $dealer->login_password,
            'remaining_user_slots' => max(0, $dealer->user_limit - $dealer->users_count),
            'subscription_status' => $dealer->subscriptionStatus(),
        ]);
    }

    public function users(Request $request, int $id)
    {
        $dealer = Dealer::withCount('users')->findOrFail($id);
        $query = $dealer->users();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.dealers.users', compact('dealer', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $dealer = Dealer::withCount('users')->findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:30'],
            'alternative_phone_number' => ['nullable', 'string', 'max:30'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'offer_price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'email' => ['required', 'email', 'max:255', Rule::unique('dealers')->ignore($dealer->id)],
            'user_limit' => ['required', 'integer', 'min:'.$dealer->users_count],
            'referral_code' => ['required', 'alpha_num:ascii', 'size:8', Rule::unique('dealers')->ignore($dealer->id)],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8'],
            'subscription_ends_at' => ['required', 'date'],
        ]);

        $validated['subscription_ends_at'] = Carbon::parse($validated['subscription_ends_at'])->endOfDay();

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        if (isset($validated['password'])) {
            $validated['login_password'] = $validated['password'];
        }
        $dealer->update($validated);

        return redirect()->route('admin.dealers.index')->with('success', 'Dealer updated successfully.');
    }

    public function destroy(int $id)
    {
        Dealer::findOrFail($id)->delete();

        return redirect()->route('admin.dealers.index')->with('success', 'Dealer deleted successfully. Existing users were retained.');
    }

    private function makePassword(string $name): string
    {
        $base = Str::studly(Str::ascii($name));

        return ($base !== '' ? $base : 'Dealer').'@123';
    }

    private function uniqueReferralCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Dealer::where('referral_code', $code)->exists());

        return $code;
    }
}
