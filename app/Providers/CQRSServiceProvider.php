<?php

namespace App\Providers;

use App\Queries\User\GetUsersQuery;
use App\Queries\User\Handlers\GetUsersHandler;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Services\CommandBus;
use App\Services\QueryBus;
use App\Commands\User\CreateUserCommand;
use App\Commands\User\Handlers\CreateUserHandler;
use App\Queries\User\GetUserByEmailQuery;
use App\Queries\User\Handlers\GetUserByEmailHandler;

class CQRSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBus::class, function(Application $app) {
            $bus = new CommandBus();
            $bus->register(CreateUserCommand::class, CreateUserHandler::class);
            return $bus;
        });

        $this->app->singleton(QueryBus::class, function(Application $app) {
            $bus = new QueryBus();
            $bus->register(GetUserByEmailQuery::class, GetUserByEmailHandler::class);
            $bus->register(GetUsersQuery::class, GetUsersHandler::class);
            return $bus;
        });
    }
}
