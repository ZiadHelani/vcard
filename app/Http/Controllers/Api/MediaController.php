<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CardGallery;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function destroy(Media $media): \Illuminate\Http\JsonResponse
    {
        if ($media->model_type === CardGallery::class) {
            CardGallery::query()->where('id', $media->model_id)->delete();
        }
        $media->delete();
        return response()->json([
            'success' => true,
            'message' => 'Deleted Successfully',
        ]);
    }
}
