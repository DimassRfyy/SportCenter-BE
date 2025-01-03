<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookingTransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/category/{category:slug}', [CategoryController::class, 'show']);
Route::apiResource('/categories', CategoryController::class);

Route::get('/city/{city:slug}', [CityController::class, 'show']);
Route::apiResource('/cities', CityController::class);

Route::get('/place/{place:slug}', [PlaceController::class, 'show']);
Route::apiResource('/places', PlaceController::class);

Route::post('/booking-transactions', [BookingTransactionController::class, 'store']);
Route::post('/check-bookings', [BookingTransactionController::class, 'check-booking']);