<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserProfileUpdater
{
    public function update(Request $request, User $user): void
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:5000'],
            'destination' => ['nullable', 'string', 'max:255'],
            'language' => ['sometimes', 'required', 'in:en,mr,hi,gu,bn,te,ta,kn,pa'],
            'subscription_started_at' => ['required', 'date'],
            'subscription_ends_at' => [
                'required', 'date', 'after_or_equal:subscription_started_at',
                'before_or_equal:'.$user->created_at->copy()->addYear()->format('Y-m-d H:i:s'),
            ],
            'approval_status' => ['sometimes', 'required', 'in:pending,approved,disapproved'],
            'password' => ['nullable', 'string', 'min:8'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'remove_profile_photo' => ['sometimes', 'boolean'],
            'remove_logo' => ['sometimes', 'boolean'],
        ]);

        if (isset($data['approval_status']) && $data['approval_status'] !== $user->approval_status && ! $user->hasExpiredTrial()) {
            throw ValidationException::withMessages([
                'approval_status' => 'Approval can only be changed after the subscription has ended.',
            ]);
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        foreach (['profile_photo', 'logo'] as $field) {
            unset($data[$field]);
            if ($request->boolean('remove_'.$field)) {
                $data[$field] = null;
            }
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('users/'.$user->id, 'public');
                $data[$field] = Storage::url($path);
            }
            unset($data['remove_'.$field]);
        }

        $user->update($data);
    }
}
