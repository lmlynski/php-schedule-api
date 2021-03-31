<?php

declare(strict_types=1);

namespace App\Domain\Task;

use App\Application\Task\Query\UserFilter;

interface TaskRepositoryInterface
{
    public function supports(string $type): bool;

    public function findByGuid(string $guid): Task;

    public function findAllBy(UserFilter $filter): array;

    public function add(Task $task): void;

    public function save(Task $task): void;
}
