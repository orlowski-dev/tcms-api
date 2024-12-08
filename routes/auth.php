<?php

use App\Http\Controllers\Auth\AuthenticateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'id'
], function () {
    Route::post('/login', [AuthenticateController::class, 'authenticate']);
    Route::post('/logout', [AuthenticateController::class, 'destroy']);
    Route::post('/change-password', [AuthenticateController::class, 'changePassword']);
});
