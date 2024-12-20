<?php

namespace App\Http\SearchParams\V1;

use App\Http\SearchParams\BaseSearchParams;

class UserSearchParams extends BaseSearchParams
{
    protected $relationNames = [
        'profile'
    ];

    protected $safeParams = [
        'email' => ['eq'],
        'name' => ['eq']
    ];
}
