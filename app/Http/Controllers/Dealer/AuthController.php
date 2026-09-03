<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('dealer')->check()) {
            return redirect()->route('dealer.dashboard');
        }

        return view('dealer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['is_active'] = true;
        if (Auth::guard('dealer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dealer.dashboard'));
        }

        return back()->withErrors(['email' => 'The provided credentials are invalid or the account is inactive.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('dealer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dealer.login');
    }

    public function updateProfile(Request $request)
    {
        $dealer = Auth::guard('dealer')->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:30'],
            'alternative_phone_number' => ['nullable', 'string', 'max:30'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'offer_price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'email' => ['required', 'email', Rule::unique('dealers')->ignore($dealer->id)],
            'referral_code' => ['required', 'alpha_num:ascii', 'size:8', Rule::unique('dealers')->ignore($dealer->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        } else {
            $validated['login_password'] = $validated['password'];
        }
        $dealer->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
