<?php

namespace App\Queries;

interface QueryHandler
{
    public function handle(Query $query): mixed;
}
