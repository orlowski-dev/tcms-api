<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'api/v1',
    'middleware' => 'auth:sanctum'
], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
