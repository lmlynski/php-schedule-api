<?php

declare(strict_types=1);

namespace App\Application\Task\Command\Builder;

use App\Application\Task\Command\ChangeTaskStatusCommand;

class ChangeTaskStatusCommandBuilder
{
    public static function buildFromRequestData(array $requestData): ChangeTaskStatusCommand
    {
        return new ChangeTaskStatusCommand($requestData['guid'], $requestData['status']);
    }
}
