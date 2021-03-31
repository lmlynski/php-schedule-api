<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

use App\Application\CommandHandlerInterface;
use App\Application\CommandInterface;
use App\Infrastructure\Repository\Task\Resolver\TaskWriteRepositoryResolverInterface;

class ChangeTaskStatusCommandHandler implements CommandHandlerInterface
{
    private TaskWriteRepositoryResolverInterface $taskWriteRepositoryResolver;

    public function __construct(TaskWriteRepositoryResolverInterface $taskWriteRepositoryResolver)
    {
        $this->taskWriteRepositoryResolver = $taskWriteRepositoryResolver;
    }

    public function handle(CommandInterface $command): void
    {
        $task = $this->taskWriteRepositoryResolver->get()->findByGuid($command->getGuid());
        $task->setStatus($command->getStatus());
        $this->taskWriteRepositoryResolver->get()->save($task);
    }
}
