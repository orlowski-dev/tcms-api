<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class Controller
{
    public function allowRequestIf(bool $condition, string $message = null)
    {
        if (!$condition) {
            throw new AccessDeniedHttpException($message ?? 'This action is unauthorized.');
        }
    }
}
