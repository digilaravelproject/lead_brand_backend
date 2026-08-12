<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Admin;
use App\Models\Dealer;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        try {
            $request->validate([
                'google_id' => 'required|string',
                'email' => 'required|email',
                'name' => 'nullable|string',
                'profile_photo' => 'nullable|string',
            ]);

            $user = User::where('email', $request->email)
                ->orWhere('google_id', $request->google_id)
                ->first();

            if (! $user) {
                // Register user automatically
                $user = User::create([
                    'email' => $request->email,
                    'google_id' => $request->google_id,
                    'name' => $request->name ?? '',
                    'profile_photo' => $request->profile_photo ?? null,
                ]);
            } else {
                // Link google_id if not already linked
                if ($request->filled('google_id')) {
                    $user->update([
                        'google_id' => $request->google_id,
                    ]);
                }
                // Fill details if they are empty
                if ($request->filled('name')) {
                    $user->update([
                        'name' => $request->name,
                    ]);
                }
                if ($request->filled('profile_photo')) {
                    $user->update([
                        'profile_photo' => $request->profile_photo,
                    ]);
                }
            }

            if (! $user->hasSubscriptionAccess()) {
                return response()->json([
                    'status' => false,
                    'message' => $user->approval_status === 'disapproved'
                        ? 'Your subscription has been disapproved by the dealer.'
                        : 'Your four-day free subscription has ended and is awaiting dealer approval.',
                ], 403);
            }

            // Revoke old tokens
            $user->tokens()->delete();

            $isNew = empty($user->name) ? 1 : 0;

            if ($isNew) {
                return response()->json([
                    'status' => true,
                    'message' => 'Google login success - profile incomplete',
                    'data' => [
                        'is_new' => 1,
                        'user' => $user,
                    ],
                ], 200);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'is_new' => 0,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user,
                ],
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            \Log::error($e);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    public function sendOtp(Request $request)
    {
        try {
            if ($request->filled('refer_code')) {
                $request->merge(['refer_code' => strtoupper(trim($request->input('refer_code')))]);
            }

            $v = Validator::make($request->all(), [
                'email' => 'required|email',
                'refer_code' => 'nullable|string|size:8|exists:dealers,referral_code',
            ]);

            if ($v->fails()) {
                return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $v->errors()], 422);
            }

            $email = $request->input('email');

            $dealer = $request->filled('refer_code')
                ? Dealer::where('referral_code', strtoupper($request->input('refer_code')))->first()
                : null;

            $now = now();
            $user = User::firstOrCreate(['email' => $email], [
                'name' => '',
                'dealer_id' => $dealer?->id,
                'password' => '',
                'subscription_started_at' => $now,
                'subscription_ends_at' => $now->copy()->addDays(4),
                'approval_status' => 'pending',
            ]);

            if (! $user->hasSubscriptionAccess()) {
                return $this->subscriptionDeniedResponse($user);
            }

            $otp = (string) random_int(1000, 9999);
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            // Send HTML Mailable
            Mail::to($email)->send(new OtpMail($otp, $user->otp_expires_at));

            // indicate whether this is a newly created user
            $isNew = $user->wasRecentlyCreated || empty($user->name) ? 1 : 0;

            return response()->json(['status' => true, 'message' => 'OTP sent', 'data' => ['email' => $email, 'expires_at' => $user->otp_expires_at, 'is_new' => $isNew]], 200);
        } catch (Exception $e) {
            Log::error('sendOtp error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to send OTP', 'error' => $e->getMessage()], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'email' => 'required|email',
                'otp' => 'required|string|size:4',
            ]);

            if ($v->fails()) {
                return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $v->errors()], 422);
            }

            $user = User::where('email', $request->input('email'))->first();
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            if (! $user->otp || $user->otp !== $request->input('otp')) {
                return response()->json(['status' => false, 'message' => 'Invalid OTP'], 400);
            }

            if ($user->otp_expires_at && Carbon::now()->greaterThan($user->otp_expires_at)) {
                return response()->json(['status' => false, 'message' => 'OTP expired'], 400);
            }

            if (! $user->hasSubscriptionAccess()) {
                return $this->subscriptionDeniedResponse($user);
            }

            $user->email_verified_at = Carbon::now();
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            // determine if user needs to complete profile
            $isNew = empty($user->name) ? 1 : 0;

            if ($isNew) {
                // don't issue token yet; require complete-setup
                return response()->json([
                    'status' => true,
                    'message' => 'OTP verified - profile incomplete',
                    'data' => [
                        'is_new' => 1,
                        'is_expired' => 1,
                        'user' => $user,
                    ],
                ], 200);
            }

            // existing user: create personal access token and return user + token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully',
                'data' => [
                    'is_new' => 0,
                    'is_expired' => 1,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user,
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('verifyOtp error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to verify OTP', 'error' => $e->getMessage()], 500);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($v->fails()) {
                return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $v->errors()], 422);
            }

            $email = $request->input('email');
            $user = User::where('email', $email)->first();
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            $otp = (string) random_int(1000, 9999);
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            Mail::to($email)->send(new OtpMail($otp, $user->otp_expires_at));

            return response()->json(['status' => true, 'message' => 'OTP resent', 'data' => ['email' => $email, 'expires_at' => $user->otp_expires_at]], 200);
        } catch (Exception $e) {
            Log::error('resendOtp error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to resend OTP', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'Unauthenticated'], 401);
            }

            $v = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'phone_number' => 'nullable|string|max:30',
                'destination' => 'nullable|string|max:255',
                'logo' => 'nullable|file|image|max:5120',
                'profile_photo' => 'nullable|file|image|max:5120',
            ]);

            if ($v->fails()) {
                return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $v->errors()], 422);
            }

            if ($request->filled('name')) {
                $user->name = $request->input('name');
            }

            if ($request->filled('phone_number')) {
                $user->phone_number = $request->input('phone_number');
            }

            if ($request->filled('destination')) {
                $user->destination = $request->input('destination');
            }

            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $user->profile_photo = Storage::url($path);
            }

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('logos', 'public');
                $user->logo = Storage::url($path);
            }

            $user->save();

            return response()->json(['status' => true, 'message' => 'Profile updated', 'data' => ['user' => $user]], 200);
        } catch (Exception $e) {
            Log::error('updateProfile error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to update profile', 'error' => $e->getMessage()], 500);
        }
    }

    public function completeSetup(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'email' => 'required|email',
                'name' => 'required|string|max:255',
                // accept multipart/form-data file upload for profile_photo
                'profile_photo' => 'nullable|file|image|max:5120',
            ]);

            if ($v->fails()) {
                return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $v->errors()], 422);
            }

            $user = User::where('email', $request->input('email'))->first();
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            if (! $user->email_verified_at) {
                return response()->json(['status' => false, 'message' => 'Email not verified'], 400);
            }

            $user->name = $request->input('name');
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                // store accessible URL (requires `php artisan storage:link` in deployment)
                $user->profile_photo = Storage::url($path);
            }
            $user->save();

            // create personal access token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Setup complete',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user,
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('completeSetup error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to complete setup', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateLanguage(Request $request)
    {
        try {
            $user = $request->user();
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'Unauthenticated'], 401);
            }

            $v = Validator::make($request->all(), [
                'language' => ['required', 'string', 'in:en,mr,hi,gu,bn,te,ta,kn,pa'],
            ]);

            if ($v->fails()) {
                return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $v->errors()], 422);
            }

            $user->language = $request->input('language');
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Language updated successfully',
                'data' => [
                    'user' => $user,
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('updateLanguage error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to update language', 'error' => $e->getMessage()], 500);
        }
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user()->load('dealer');
            $data = ['user' => $user];

            if ($user->dealer) {
                $data['dealer'] = $user->dealer;
                $data['admin'] = null;
            } else {
                $data['dealer'] = null;
                $data['admin'] = Admin::query()->first();
            }

            return response()->json(['status' => true, 'data' => $data], 200);
        } catch (Exception $e) {
            Log::error('me error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to fetch user', 'error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->user()->currentAccessToken();
            if ($token) {
                $token->delete();
            }

            return response()->json(['status' => true, 'message' => 'Logged out'], 200);
        } catch (Exception $e) {
            Log::error('logout error: '.$e->getMessage());

            return response()->json(['status' => false, 'message' => 'Failed to logout', 'error' => $e->getMessage()], 500);
        }
    }

    private function subscriptionDeniedResponse(User $user)
    {
        return response()->json([
            'status' => false,
            'message' => $user->approval_status === 'disapproved'
                ? 'Your account has not been approved.'
                : 'Your four-day free subscription has ended and is awaiting approval.',
            'data' => ['is_expired' => 0],
        ], 403);
    }
}
