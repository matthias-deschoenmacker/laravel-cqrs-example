<?php

namespace App\Queries\User\Handlers;

use App\Queries\Query;
use App\Queries\QueryHandler;
use App\Queries\User\GetUserByEmailQuery;
use App\Models\User;

class GetUserByEmailHandler implements QueryHandler
{
    public function handle(Query $query): ?User
    {
        if (!$query instanceof GetUserByEmailQuery) {
            throw new \InvalidArgumentException('Invalid query type');
        }

        return User::where('email', $query->email)->first();
    }
}
