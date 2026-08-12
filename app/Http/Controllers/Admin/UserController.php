<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserWelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereNull('dealer_id')->latest()->paginate(10);

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
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'destination' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->destination = $request->destination;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                @unlink(public_path($user->profile_photo));
            }

            $file = $request->file('profile_photo');
            $filename = time().'_photo_'.preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
            $destinationPath = public_path('uploads/users');
            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $user->profile_photo = 'uploads/users/'.$filename;
        }

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            if ($user->logo && file_exists(public_path($user->logo))) {
                @unlink(public_path($user->logo));
            }

            $file = $request->file('logo');
            $filename = time().'_logo_'.preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
            $destinationPath = public_path('uploads/users');
            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $user->logo = 'uploads/users/'.$filename;
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function approval(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'approval_status' => ['required', 'in:approved,disapproved'],
        ]);

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
