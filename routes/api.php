<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('check.token')->group(function () {
    Route::post('/v1/short-urls', [ShortUrlController::class, 'store'])->name('shortUrl.store');
    Route::get('/v1/short-urls/{shortUrl}', [ShortUrlController::class, 'redirect'])->name('shortUrl.redirect');
});




