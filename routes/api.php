<?php

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1'
], function () {
    Route::apiResource('users', UserController::class);
    Route::post('/users/{userId}/restore', [UserController::class, 'restore']);
    Route::get('/profiles/{userId}', [UserProfileController::class, 'show']);
    Route::put('/profiles/{userId}', [UserProfileController::class, 'update']);
    Route::patch('/profiles/{userId}', [UserProfileController::class, 'update']);
});
