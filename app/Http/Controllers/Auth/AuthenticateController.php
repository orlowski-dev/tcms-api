<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function changePassword(ChangePasswordRequest $request)
    {
        $requestData = $request->only('userId', 'newPassword');

        $user = User::find($requestData['userId']);
        $user->forceFill([
            'password' => Hash::make($requestData['newPassword'])
        ]);

        $user->save();

        return response()->json([
            'message' => 'Password has been changed.'
        ]);
    }
}
