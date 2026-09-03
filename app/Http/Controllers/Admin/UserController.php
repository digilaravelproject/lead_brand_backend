<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserWelcomeMail;
use App\Models\User;
use App\Services\UserProfileUpdater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::whereNull('dealer_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /** Create a user directly under the admin with a four-day trial. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $now = now();
        $user = User::create($validated + [
            'password' => $validated['password'] ?? Str::password(12),
            'subscription_started_at' => $now,
            'subscription_ends_at' => $now->copy()->addDays(4),
            'approval_status' => 'pending',
        ]);

        Mail::to($user->email)->send(new UserWelcomeMail($user, Auth::guard('admin')->user()));

        return redirect()->route('admin.users.index')
            ->with('success', 'User created with a four-day free subscription.');
    }

    /**
     * Return user details in JSON format for modals.
     */
    public function show($id)
    {
        $user = User::with('dealer')->findOrFail($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        app(UserProfileUpdater::class)->update($request, $user);

        return $user->dealer_id
            ? redirect()->route('admin.dealers.users', $user->dealer_id)->with('success', 'User updated successfully.')
            : redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function approval(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'approval_status' => ['required', 'in:approved,disapproved'],
        ]);

        if (! $user->hasExpiredTrial()) {
            throw ValidationException::withMessages([
                'approval_status' => 'Approval can only be changed after the subscription has ended.',
            ]);
        }

        $user->update(['approval_status' => $validated['approval_status']]);

        return back()->with('success', 'User subscription status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete uploaded files
        if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
            @unlink(public_path($user->profile_photo));
        }
        if ($user->logo && file_exists(public_path($user->logo))) {
            @unlink(public_path($user->logo));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
