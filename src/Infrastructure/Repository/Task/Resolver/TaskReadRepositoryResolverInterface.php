<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Task\Resolver;

use App\Domain\Task\TaskRepositoryInterface;

interface TaskReadRepositoryResolverInterface
{
    public function get(): TaskRepositoryInterface;

    public function addRepository(TaskRepositoryInterface $repository): void;
}
