<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReminderController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('vehicles', VehicleController::class);
    Route::post('/vehicles/decode-vin', [VehicleController::class, 'decodeVin']);

    Route::get('/vehicles/{vehicle}/reminders', [ReminderController::class, 'index']);
    Route::post('/vehicles/{vehicle}/reminders', [ReminderController::class, 'store']);
    Route::put('/reminders/{reminder}', [ReminderController::class, 'update']);
    Route::post('/reminders/{reminder}/complete', [ReminderController::class, 'complete']);
    Route::delete('/reminders/{reminder}', [ReminderController::class, 'destroy']);

    Route::get('/shops', [ShopController::class, 'index']);
    Route::get('/shops/{shop}', [ShopController::class, 'show']);
    Route::post('/shops/{shop}/favorite', [ShopController::class, 'addFavorite']);
    Route::delete('/shops/{shop}/favorite', [ShopController::class, 'removeFavorite']);
    Route::get('/shops/favorites', [ShopController::class, 'favorites']);

    Route::get('/vehicles/{vehicle}/services', [ServiceRecordController::class, 'index']);
    Route::post('/vehicles/{vehicle}/services', [ServiceRecordController::class, 'store']);
    Route::get('/services/{service}', [ServiceRecordController::class, 'show']);
    Route::put('/services/{service}', [ServiceRecordController::class, 'update']);
    Route::delete('/services/{service}', [ServiceRecordController::class, 'destroy']);
});
