<?php

namespace App\Queries\User;

use App\Queries\Query;

class GetUserByEmailQuery implements Query
{
    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
