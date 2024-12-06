<?php

namespace App\Http\Filters\V1;

use App\Http\Filters\ApiFilter;

class UsersFilter extends ApiFilter
{
    protected $safeParams = [
        'email' => ['eq'],
        'name' => ['eq'],
        'roleId' => ['eq', 'neq']
    ];

    protected $columnMap = [
        'roleId' => 'role_id'
    ];
}
