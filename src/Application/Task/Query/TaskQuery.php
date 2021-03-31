<?php

declare(strict_types=1);

namespace App\Application\Task\Query;

use App\Application\Task\Query\Model\TaskView;
use App\Infrastructure\Repository\Task\Resolver\TaskReadRepositoryResolverInterface;
use App\Common\UserGuidResolverInterface;
use DateTimeImmutable;

class TaskQuery
{
    private TaskReadRepositoryResolverInterface $taskReadRepositoryResolver;
    private UserGuidResolverInterface $userGuidResolverInterface;

    public function __construct(
        TaskReadRepositoryResolverInterface $taskReadRepositoryResolver,
        UserGuidResolverInterface $userGuidResolverInterface
    ) {
        $this->taskReadRepositoryResolver = $taskReadRepositoryResolver;
        $this->userGuidResolverInterface = $userGuidResolverInterface;
    }

    public function getByGuid(string $guid): TaskView
    {
        $task = $this->taskReadRepositoryResolver->get()->findByGuid($guid);

        return new TaskView(
            $task->getGuid(),
            $task->getTitle(),
            $task->getDescription(),
            $task->getAssigneeId(),
            $task->getStatus(),
            $task->getDueDate()->format('Y-m-d')
        );
    }

    public function getMyTodayTasks(): array
    {
        $filter = (new UserFilter())
            ->setFieldCondition('dueDate', (new DateTimeImmutable())->format('Y-m-d'))
            ->setFieldCondition('assigneeId', $this->userGuidResolverInterface->resolve());

        $tasks = $this->taskReadRepositoryResolver->get()->findAllBy($filter);

        $viewResult = [];
        foreach ($tasks as $task) {
            $viewResult[] = new TaskView(
                $task->getGuid(),
                $task->getTitle(),
                $task->getDescription(),
                $task->getAssigneeId(),
                $task->getStatus(),
                $task->getDueDate()->format('Y-m-d')
            );
        }

        return $viewResult;
    }
}
