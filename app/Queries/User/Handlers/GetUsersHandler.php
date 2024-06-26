<?php

namespace App\Queries\User\Handlers;

use App\Queries\Query;
use App\Queries\QueryHandler;
use App\Models\User;
use App\Queries\User\GetUsersQuery;
use Illuminate\Support\Collection;

class GetUsersHandler implements QueryHandler
{
    public function handle(Query $query): Collection
    {
        if (!$query instanceof GetUsersQuery) {
            throw new \InvalidArgumentException('Invalid query type');
        }

        return User::all();
    }
}
