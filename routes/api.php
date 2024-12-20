<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1'
], function () {
    Route::apiResource('users', UserController::class);
    Route::post('/users/{userId}/restore', [UserController::class, 'restore']);
});
