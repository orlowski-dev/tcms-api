<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'api/v1',
    'middleware' => 'auth:sanctum'
], function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'user' => $user,
            'role' => $user->role->name,
            'permissions' => $user->getPermissions()
        ]);
    });
});
