<?php

namespace App\Http\Controllers;

use App\Commands\User\CreateUserCommand;
use App\Queries\User\GetUserByEmailQuery;
use App\Queries\User\GetUsersQuery;
use Illuminate\Http\Request;
use App\Services\CommandBus;
use App\Services\QueryBus;

class UserController
{
    protected CommandBus $commandBus;
    protected QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function create(Request $request)
    {
        $command = new CreateUserCommand(
            $request->input('name'),
            $request->input('email'),
            $request->input('password')
        );
        $this->commandBus->dispatch($command);

        return response()->json(['message' => 'User created successfully']);
    }

    public function getUserByEmail($email)
    {
        $query = new GetUserByEmailQuery($email);
        $user = $this->queryBus->dispatch($query);

        return response()->json($user);
    }

    public function getUsers()
    {
        $query = new GetUsersQuery();
        $user = $this->queryBus->dispatch($query);

        return response()->json($user);
    }
}
