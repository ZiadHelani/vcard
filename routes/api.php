<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')
    ->controller(\App\Http\Controllers\Api\AuthController::class)
    ->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('login/google', 'loginGoogle');
        Route::delete('logout', 'logout')->middleware('auth:sanctum');
        Route::post('forgot-password', 'forgotPassword');
        Route::put('reset-password', 'resetPassword');
        Route::post('verify-account', 'verifyAccount')->middleware('auth:sanctum');
        Route::post('send-otp-to-verify-account', 'sendOtpToVerifyAccount');
    });

Route::prefix('plans')
    ->controller(\App\Http\Controllers\Api\PlanController::class)
    ->group(function () {
        Route::get('get-all', 'getAllPlans');
        Route::get('show/{plan}', 'show');
    });

Route::prefix('subscriptions')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\SubscriptionController::class)
    ->group(function () {
        Route::get('get-all', 'getAllSubscriptions')->middleware('admin');
        Route::post('create', 'createNewSubscription');
        Route::get('get-subscription', 'getSubscription');
    });

Route::prefix('cards')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\CardController::class)
    ->group(function () {
        Route::get('get-all', 'getAllCards');
        Route::get('show/{card}', 'show');
        Route::post('store', 'store');
        Route::delete('delete/{card}', 'delete');
        Route::post('update/{card}', 'update');
        Route::put('update/{card}/status', 'updateCardStatus');
        Route::delete('remove-qrcode-logo/{card}', 'removeQrCodeLogo');
        Route::get('download-vcard/{card}', 'downloadVCard')->withoutMiddleware('auth:sanctum');
        Route::post('send-mail/{card}', 'sendMail')->withoutMiddleware('auth:sanctum');
    });

Route::prefix('user')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\UserController::class)
    ->group(function () {
        Route::get('profile', 'getProfile');
        Route::post('profile', 'updateProfile');
        Route::patch('change-password', 'changePassword');
    });

Route::prefix('contacts')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\ContactController::class)
    ->group(function () {
        Route::get('get-all', 'getAllContacts');
        Route::get('show-single-contact/{contact}', 'showSingleContact');
        Route::post('store', 'store');
        Route::delete('delete/{contact}', 'delete');
        Route::post('update/{contact}', 'update');
        Route::patch('toggle-active/{contact}', 'toggleActive');
    });

Route::prefix('cards')
    ->controller(\App\Http\Controllers\Api\CardController::class)
    ->group(function () {
        Route::post('store', 'store')->middleware('auth:sanctum');
        Route::get('show/{card}', 'show');
    });

Route::prefix('cards/personal-details')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\PersonalDetailCardController::class)
    ->group(function () {
        Route::post('store', 'store');
        Route::post('update', 'update');
    });

Route::prefix('cards/faqs')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\CardFaqController::class)
    ->group(function () {
        Route::post('store', 'store');
        Route::post('update/{card_faq}', 'update');
        Route::delete('delete/{card_faq}', 'delete');
    });

Route::prefix('cards/buttons')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\CardButtonController::class)
    ->group(function () {
        Route::post('store', 'store');
        Route::post('update/{card_button}', 'update');
        Route::delete('delete/{card_button}', 'delete');
    });

Route::prefix('cards/reviews')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\CardReviewController::class)
    ->group(function () {
        Route::post('store', 'store');
        Route::post('update/{card_review}', 'update');
        Route::delete('delete/{card_review}', 'delete');
    });

Route::prefix('home')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\HomePageController::class)
    ->group(function () {
        Route::get('data', 'getHomePageData');
    });

Route::prefix('support')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\SupportController::class)
    ->group(function () {
        Route::post('store', 'store');
    });

Route::prefix('serial-devices')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\SerialDeviceController::class)
    ->group(function () {
        Route::post('check-device', 'checkDevice');
        Route::post('link-device', 'linkDevice');
    });

Route::prefix('notifications')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\NotificationController::class)
    ->group(function () {
        Route::get('get-all', 'getAllNotifications');
        Route::delete('delete/{notification}', 'delete');
        Route::put('read-all', 'readAll');
        Route::put('read-single-notification/{notification}', 'readSingleNotification');
    });

Route::prefix('reorder')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\Api\ReorderController::class)
    ->group(function () {
        Route::post('data/{model}', 'reorder');
    });


Route::prefix('users')
    ->middleware(['auth:sanctum', 'admin'])
    ->controller(\App\Http\Controllers\Api\UserController::class)
    ->group(function () {
        Route::get('get-all', 'getAllUsers');
        Route::post('store', 'store');
        Route::delete('delete/{user}', 'deleteUser');
        Route::patch('{user}/toggle-verification', 'toggleUserVerification');
    });

Route::prefix('admin/subscriptions')
    ->middleware(['auth:sanctum', 'admin'])
    ->controller(\App\Http\Controllers\Api\SubscriptionController::class)
    ->group(function () {
        Route::get('get-all', 'getAllSubscriptionsForAdmin');
        Route::get('{subscription}', 'getSubscriptionForAdmin');
        Route::put('{subscription}', 'updateSubscription');
        Route::delete('delete/{subscription}', 'deleteSubscription');
        Route::patch('{subscription}/toggle-status', 'toggleSubscriptionStatus');
        Route::post('store', 'store')->name('store');
    });


Route::prefix('media')
    ->middleware(['auth:sanctum'])
    ->controller(\App\Http\Controllers\Api\MediaController::class)
    ->group(function () {
        Route::delete('destroy/{media}', 'destroy');
    });


Route::prefix('privacy-content')
    ->controller(\App\Http\Controllers\Api\PrivacyContentController::class)
    ->group(function () {
        Route::get('/', 'getPrivacyContent');
    });
