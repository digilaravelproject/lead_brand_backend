<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Mail\UserWelcomeMail;
use App\Models\Dealer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        $dealer = Auth::guard('dealer')->user();
        $users = $dealer->users()->latest()->paginate(10);
        $usedSlots = $dealer->users()->count();

        return view('dealer.users.index', compact('dealer', 'users', 'usedSlots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($validated) {
            $dealer = Dealer::whereKey(Auth::guard('dealer')->id())->lockForUpdate()->firstOrFail();
            if ($dealer->users()->count() >= $dealer->user_limit) {
                throw ValidationException::withMessages([
                    'user_limit' => "Your user allowance of {$dealer->user_limit} has been reached.",
                ]);
            }

            $now = now();
            $user = User::create($validated + [
                'dealer_id' => $dealer->id,
                'password' => Str::password(12),
                'subscription_started_at' => $now,
                'subscription_ends_at' => $now->copy()->addDays(4),
                'approval_status' => 'pending',
            ]);

            Mail::to($user->email)->send(new UserWelcomeMail($user, $dealer));
        });

        return back()->with('success', 'User created with a four-day free subscription.');
    }

    public function show(int $id)
    {
        return response()->json($this->findOwnedUser($id)->load('dealer'));
    }

    public function update(Request $request, int $id)
    {
        $user = $this->findOwnedUser($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'subscription_started_at' => ['required', 'date'],
            'subscription_ends_at' => [
                'required',
                'date',
                'after_or_equal:subscription_started_at',
                'before_or_equal:'.$user->created_at->copy()->addYear()->format('Y-m-d H:i:s'),
            ],
        ]);
        $user->update($validated);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->findOwnedUser($id)->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function approval(Request $request, int $id)
    {
        $user = $this->findOwnedUser($id);
        $validated = $request->validate(['approval_status' => ['required', 'in:approved,disapproved']]);

        if (! $user->hasExpiredTrial()) {
            throw ValidationException::withMessages(['approval_status' => 'Approval can only be changed after the four-day trial has ended.']);
        }

        $user->update(['approval_status' => $validated['approval_status']]);

        return back()->with('success', 'User subscription status updated.');
    }

    private function findOwnedUser(int $id): User
    {
        return Auth::guard('dealer')->user()->users()->findOrFail($id);
    }
}
