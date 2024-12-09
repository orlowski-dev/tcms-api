<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Closure;

class EnsureRememberTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userRememberToken = $request->user()->getRememberToken();
        $rememberCookie = $request->cookie('remember_token');

        if ($userRememberToken !== $rememberCookie) {
            throw new AccessDeniedHttpException('This action is unauthorized. Remember cookie is invalid.');
        }

        return $next($request);
    }
}
