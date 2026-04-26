<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserSubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSubscriptionRequest;
use App\Http\Resources\Api\SubscriptionResource;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{

    public function getAllSubscriptions(): \Illuminate\Http\JsonResponse
    {
        $subscriptions = Subscription::query()
            ->with(['plan'])
            ->paginate(PAGINATE_LIMIT);
        return response()->json([
            'subscriptions' => SubscriptionResource::collection($subscriptions),
        ]);
    }

    public function createNewSubscription(StoreSubscriptionRequest $request): ?\Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        if ($user?->hasActiveSubscription()) {
            return response()->json([
                'message' => "You already have an active subscription",
            ], 400);
        }
        $plan = Plan::query()->where('id', $request->plan_id)->first();
        $start = Carbon::now();
        $subscription = $user?->subscriptions()->create([
            'plan_id' => $request->plan_id,
            'start_at' => $start->format('Y-m-d'),
            'end_at' => $start->addMonth()->format('Y-m-d'),
            'price' => $plan->price,
            'status' => UserSubscriptionStatus::ACTIVE,
        ]);
        return response()->json([
            'message' => "Subscription created successfully, go to payment page to continue",
            'subscription' => SubscriptionResource::make($subscription),
        ], 200);
    }

    public function getSubscription(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        if ($user?->hasActiveSubscription()) {
            return response()->json([
                'subscription' => SubscriptionResource::make($user?->activeSubscriptionNow()),
            ], 200);
        }

        return response()->json([
            'message' => "You don't have an active subscription",
        ], 400);
    }

    // Admin methods
    public function getAllSubscriptionsForAdmin(): \Illuminate\Http\JsonResponse
    {
        $subscriptions = Subscription::query()
            ->with(['user', 'plan'])
            ->paginate(PAGINATE_LIMIT);
        return response()->json([
            'subscriptions' => SubscriptionResource::collection($subscriptions)->response()->getData(true),
        ]);
    }

    public function getSubscriptionForAdmin(Subscription $subscription): \Illuminate\Http\JsonResponse
    {
        $subscription->load(['user', 'plan']);
        return response()->json([
            'subscription' => SubscriptionResource::make($subscription),
        ]);
    }

    public function updateSubscription(Subscription $subscription, Request $request): \Illuminate\Http\JsonResponse
    {
        $subscription->update($request->only(['status', 'start_at', 'end_at', 'price']));
        return response()->json([
            'message' => 'Subscription updated successfully',
            'subscription' => SubscriptionResource::make($subscription->fresh(['user', 'plan'])),
        ]);
    }

    public function deleteSubscription(Subscription $subscription): \Illuminate\Http\JsonResponse
    {
        $subscription->delete();
        return response()->json([
            'message' => 'Subscription deleted successfully',
        ]);
    }

    public function toggleSubscriptionStatus(Subscription $subscription): \Illuminate\Http\JsonResponse
    {
        $newStatus = $subscription->status === UserSubscriptionStatus::ACTIVE
            ? UserSubscriptionStatus::CANCELLED
            : UserSubscriptionStatus::ACTIVE;

        $subscription->update(['status' => $newStatus]);

        return response()->json([
            'message' => 'Subscription status updated successfully',
            'subscription' => SubscriptionResource::make($subscription->fresh(['user', 'plan'])),
        ]);
    }

    public function store(\App\Http\Requests\Admin\StoreSubscriptionRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = User::query()->where('id', $data['user_id'])->first();

        if ($user?->hasActiveSubscription()) {
            return response()->json([
                'message' => "The User already have an active subscription",
            ], 400);
        }
        $plan = Plan::query()->where('id', $request->plan_id)->first();

        $subscription = $user?->subscriptions()->create([
            'plan_id' => $request->plan_id,
            'start_at' => Carbon::parse($data['from'])->format('Y-m-d'),
            'end_at' => Carbon::parse($data['to'])->format('Y-m-d'),
            'price' => $plan?->price * $data['number_of_months'],
            'status' => UserSubscriptionStatus::ACTIVE,
        ]);
        return response()->json([
            'message' => "Subscription created successfully",
            'subscription' => SubscriptionResource::make($subscription),
        ], 201);
    }
}
