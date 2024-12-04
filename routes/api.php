<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'api/v1',
    'middleware' => 'auth:sanctum'
], function () {
    Route::apiResource('users', UserController::class);
    Route::post('/users/{id}/restore', [UserController::class, 'restore']);
});
