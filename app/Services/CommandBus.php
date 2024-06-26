<?php

namespace App\Services;

use App\Commands\Command;
use App\Commands\CommandHandler;

class CommandBus
{
    protected array $handlers = [];

    public function register(string $command, string $handler): void
    {
        $this->handlers[$command] = $handler;
    }

    public function dispatch(Command $command): mixed
    {
        $handler = $this->handlers[get_class($command)];
        return (new $handler())->handle($command);
    }
}
