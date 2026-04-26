<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getAllNotifications(Request $request)
    {
        $unread = $request->has('unread') && $request->input('unread') == 1;
        $user = Auth::guard('sanctum')->user();
        if ($unread) {
            $notifications = $user?->unreadNotifications()
                ->orderBy('created_at', 'desc')
                ->paginate(PAGINATE_LIMIT);
        } else {
            $notifications = $user?->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate(PAGINATE_LIMIT);
        }
        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function delete(string $notification): \Illuminate\Http\JsonResponse
    {
        DatabaseNotification::query()->where('id', $notification)->delete();
        return response()->json([
            'message' => "Deleted successfully",
        ]);
    }

    public function readAll(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        $user?->unreadNotifications->markAsRead();
        return response()->json([
            'Read All Successfully',
        ]);
    }

    public function readSingleNotification(string $notification): \Illuminate\Http\JsonResponse
    {
        $notification = DatabaseNotification::query()->where('id', $notification)->first();
        $notification?->markAsRead();
        return response()->json([
            'message' => 'Read successfully',
        ]);
    }
}
