<?php

namespace App\Commands\User\Handlers;

use App\Commands\Command;
use App\Commands\CommandHandler;
use App\Commands\User\CreateUserCommand;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserHandler implements CommandHandler
{
    public function handle(Command $command): void
    {
        if (!$command instanceof CreateUserCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $user = new User();
        $user->name = $command->name;
        $user->email = $command->email;
        $user->password = Hash::make($command->password);
        $user->save();
    }
}
