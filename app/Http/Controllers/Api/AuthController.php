<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Exception;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($v->fails()) {
                return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $v->errors()], 422);
            }

            $email = $request->input('email');

            $user = User::firstOrCreate(['email' => $email], ['name' => '']);

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

    public function me(Request $request)
    {
        try {
            return response()->json(['status' => true, 'data' => ['user' => $request->user()]], 200);
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
}
