<?php

namespace App\Commands;

interface CommandHandler
{
    public function handle(Command $command): void;
}
