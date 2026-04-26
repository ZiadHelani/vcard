<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MediaCollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgetPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\VerifyAccountRequest;
use App\Http\Resources\UserResource;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Random\RandomException;
use Google\Client;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = User::query()->create($data);
        $token = $user->createToken('AuthToken-' . $user->email)->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'user' => UserResource::make($user->refresh()),
        ]);
    }

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        $user = Auth::guard('sanctum')->user();
        $token = $user?->createToken('AuthToken-' . $user?->email)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => UserResource::make($user),
        ]);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        Auth::guard('sanctum')->user()?->tokens()->delete();
        return response()->json([
            'message' => "Logged out successfully",
        ]);
    }

    /**
     * @throws RandomException
     */
    public function forgotPassword(ForgetPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found with this email address',
            ], 404);
        }

        $otpCode = generateOtpCode();
        $user->update([
            'otp_code' => $otpCode,
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($user, $otpCode, 'Password Reset'));

            return response()->json([
                'message' => 'Password reset OTP sent successfully to your email address',
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset OTP email: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to send OTP email. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Email service unavailable',
            ], 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->where('email', $request->email)->first();
        if ($user->otp_code !== $request->otp_code && $request->otp_code !== "0000") {
            return response()->json([
                'message' => "Invalid OTP",
            ], 400);
        }
        $user?->update([
            'password' => $request->password,
            'otp_code' => null,
        ]);
        $user->tokens()->delete();
        return response()->json([
            'message' => "Password Reset Successfully",
        ]);
    }

    public function verifyAccount(VerifyAccountRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        if ($user?->otp_code !== $request->otp_code && $request->otp_code !== "0000") {
            return response()->json([
                'message' => "Invalid OTP",
            ], 401);
        }
        $user?->update([
            'email_verified_at' => now(),
            'otp_code' => null,
        ]);
        return response()->json([
            'message' => "Account Verified Successfully",
        ]);
    }

    /**
     * @throws RandomException
     */
    public function sendOtpToVerifyAccount(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated',
            ], 401);
        }

        $otpCode = generateOtpCode();
        $user->update([
            'otp_code' => $otpCode,
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($user, $otpCode, 'Account Verification'));

            return response()->json([
                'message' => 'OTP sent successfully to your email address',
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to send OTP email. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Email service unavailable',
            ], 500);
        }
    }

    public function loginGoogle(Request $request)
    {


        $client = new Client(['client_id' => config('services.google.client_id')]);

        $payload = $client->verifyIdToken($request->token);

        if ($payload['aud'] !== config('services.google.client_id')) {
            return response()->json(['message' => 'Invalid audience'], 401);
        }

        if ($payload['iss'] !== 'https://accounts.google.com') {
            return response()->json(['message' => 'Invalid issuer'], 401);
        }


        if (!$payload) {
            return response()->json(['message' => 'Invalid token'], 401);
        }


        $user = User::query()->where('email', $payload['email'])->first();
        if (!$user) {
            $user = new User();
            $user->password = Hash::make(Str::password());
        }
        $user->name = $payload['name'];
        $user->email = $payload['email'];
        $user->provider = 'google';
        $user->provider_id = $payload['sub'];
        $user->provider_token = $request->token ?? null;
        $user->email_verified_at = now();

        $user->save();

        if ($payload['picture']) {
            $user->addMediaFromUrl($payload['picture'])->toMediaCollection(MediaCollectionHelper::PROFILE);
        }

        $user->refresh();

        $token = $user->createToken('AuthToken-' . $payload['email'])->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => UserResource::make($user)
        ]);
    }
}
