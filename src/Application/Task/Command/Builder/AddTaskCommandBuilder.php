<?php

declare(strict_types=1);

namespace App\Application\Task\Command\Builder;

use App\Application\Task\Command\AddTaskCommand;
use DateTimeImmutable;

class AddTaskCommandBuilder
{
    public static function buildFromRequestData(string $guid, array $requestData): AddTaskCommand
    {
        return new AddTaskCommand(
            $guid,
            $requestData['title'],
            $requestData['description'],
            $requestData['assigneeId'],
            $requestData['status'],
            new DateTimeImmutable($requestData['dueDate'])
        );
    }
}
