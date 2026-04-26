<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRoleEnum;
use App\Enums\UserSubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Api\UpdatePasswordRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Requests\Api\VerifyAccountRequest;
use App\Http\Resources\UserResource;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Random\RandomException;

class UserController extends Controller
{
    public function getProfile(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'user' => UserResource::make(Auth::guard('sanctum')->user()),
        ]);
    }

    /**
     * @throws RandomException
     * @throws ConnectionException
     */
    public function updateProfile(UpdateProfileRequest $request): \Illuminate\Http\JsonResponse
    {
        $message = "Updated Successfully";
        $user = Auth::guard('sanctum')->user();
        $user?->update($request->validated());
        if ($user?->isDirty('email')) {
            $message = "We sent you an OTP to verify your new email";
            $user?->update([
                'otp_code' => generateOtpCode(),
                'email_verified_at' => null,
            ]);
            Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://api.emailjs.com/api/v1.0/email/send", [
                'service_id' => "service_89s3pwn",
                'template_id' => "template_os6d24i",
                'user_id' => "cXQZFVOw8VHt7agHH",
                'accessToken' => "fOIfrCkPinBEF_XizH17U",
                'template_params' => [
                    'email' => $user?->email,
                    'otp_code' => $user?->otp_code,
                ],
            ]);
        }
        if ($request->hasFile('image')) {
            $user?->clearMediaCollection($user?->getCollection());
            $user?->addMediaFromRequest('image')->toMediaCollection($user?->getCollection());
        }
        return response()->json([
            'message' => $message,
            'updated_email' => $user?->isDirty('email'),
            'user' => UserResource::make($user),
        ]);
    }

    public function changePassword(UpdatePasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        $user?->update([
            'password' => $request->password,
        ]);
        return response()->json([
            'message' => 'Password Updated Successfully',
        ]);
    }

    public function getAllUsers(): \Illuminate\Http\JsonResponse
    {
        $users = User::query()
            ->where('role', UserRoleEnum::USER)
            ->paginate(PAGINATE_LIMIT);
        return response()->json([
            'users' => UserResource::collection($users)->response()->getData(true),
        ]);
    }

    public function toggleUserVerification(User $user): \Illuminate\Http\JsonResponse
    {
        // Toggle the email verification status
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $message = 'User verification removed successfully';
        } else {
            $user->update(['email_verified_at' => now()]);
            $message = 'User verified successfully';
        }

        return response()->json([
            'message' => $message,
            'user' => UserResource::make($user->fresh()),
        ]);
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'Deleted successfully',
        ]);
    }

    public function store(StoreUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRoleEnum::USER,
        ]);
        if ($request->hasFile('image')) {
            $user->addMediaFromRequest('image')->toMediaCollection($user->getCollection());
        }
        $plan = Plan::query()->firstWhere('id', $data['plan_id']);
        Subscription::query()->create([
            'plan_id' => $data['plan_id'],
            'user_id' => $user->id,
            'price' => $plan?->price ?? 0,
            'start_at' => Carbon::parse($data['from'])->format('Y-m-d'),
            'end_at' => Carbon::parse($data['to'])->format('Y-m-d'),
            'status' => UserSubscriptionStatus::ACTIVE,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'User Created Successfully',
        ], 201);
    }
}
