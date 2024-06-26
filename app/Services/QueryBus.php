<?php

namespace App\Services;

use App\Queries\Query;

class QueryBus
{
    protected array $handlers = [];

    public function register(string $query, string $handler): void
    {
        $this->handlers[$query] = $handler;
    }

    public function dispatch(Query $query)
    {
        $handler = $this->handlers[get_class($query)];
        return (new $handler())->handle($query);
    }
}

