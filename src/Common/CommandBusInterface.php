<?php

declare(strict_types=1);

namespace App\Common;

use App\Application\CommandInterface;
use App\Application\CommandHandlerInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;

    public function registerHandler(string $commandClassName, CommandHandlerInterface $handler): void;
}
