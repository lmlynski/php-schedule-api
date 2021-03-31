<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

use App\Application\CommandHandlerInterface;
use App\Application\CommandInterface;
use App\Infrastructure\Repository\Task\Resolver\TaskWriteRepositoryResolverInterface;
use App\Domain\Task\Task;

class AddTaskCommandHandler implements CommandHandlerInterface
{
    private TaskWriteRepositoryResolverInterface $taskWriteRepositoryResolver;

    public function __construct(TaskWriteRepositoryResolverInterface $taskWriteRepositoryResolver)
    {
        $this->taskWriteRepositoryResolver = $taskWriteRepositoryResolver;
    }

    public function handle(CommandInterface $command): void
    {
        $task = new Task(
            $command->getGuid(),
            $command->getTitle(),
            $command->getDescription(),
            $command->getAssigneeId(),
            $command->getStatus(),
            $command->getDueDate()
        );

        $this->taskWriteRepositoryResolver->get()->add($task);
    }
}
