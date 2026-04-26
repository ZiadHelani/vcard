<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('cards/{slug}', function ($slug) {

    $card = \App\Models\Card::query()
        ->with(['personalDetails'])
        ->where('slug', $slug)
        ->first();

    return view('card-preview', compact('card'));

})->name('card-slug.preview');
