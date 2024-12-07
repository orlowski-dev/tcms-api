<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function authenticate(LoginRequest $loginRequest)
    {
        $loginRequest->authenticate();

        $loginRequest->session()->regenerate();
        $loginRequest->session()->regenerateToken();

        $user = Auth::user();

        return new UserResource($user);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Use has been logged out.'
        ])->withCookie(cookie()->forget('forget_cookie'));
    }
}
