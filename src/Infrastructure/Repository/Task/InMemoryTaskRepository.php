<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Task;

use App\Application\Task\Query\UserFilter;
use App\Domain\Task\Task;
use App\Domain\Task\TaskRepositoryInterface;
use App\Domain\Task\Exception\TaskNotFoundException;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    const TYPE = 'memory';

    private array $tasks = [];

    public function supports(string $type): bool
    {
        return $type === self::TYPE;
    }

    public function findByGuid(string $guid): Task
    {
        if (!empty($this->tasks[$guid])) {
            return $this->tasks[$guid];
        }

        throw TaskNotFoundException::forGuid($guid);
    }

    public function add(Task $task): void
    {
        $this->tasks[$task->getGuid()] = $task;
    }

    public function save(Task $task): void
    {
        $this->tasks[$task->getGuid()] = $task;
    }

    public function findAllBy(UserFilter $filter): array
    {
        $result = [];
        foreach ($this->tasks as $task) {
            if (empty($filter->getFields())) {
                continue;
            }
            foreach ($filter->getFields() as $key => $field) {
                $value = $task->{'get' . ucfirst($key)}();
                if ($value instanceof \DateTimeImmutable) {
                    $value = $value->format('Y-m-d');
                }
                if ($value !== $field) {
                    continue 2;
                }
            }

            $result[] = $task;
        }

        return array_slice($result, $filter->getOffset(), $filter->getLimit());
    }

    public function setExampleData()
    {
        $this->tasks['19265534-5218-492f-9cfc-d051a0d2e8d0'] = new Task(
            '19265534-5218-492f-9cfc-d051a0d2e8d0',
            'title one',
            'description one',
            '5966c003-b09b-40a3-abc7-cfcb6c31a954',
            'new',
            new \DateTimeImmutable('2021-03-27')
        );
        $this->tasks['e6752afc-dd94-4128-aa48-4c13e032e9c4'] = new Task(
            'e6752afc-dd94-4128-aa48-4c13e032e9c4',
            'title two',
            'description two',
            '05fe9fbd-273b-4878-8d4b-349e50318c2d',
            'done',
            new \DateTimeImmutable('2021-04-09')
        );
        $this->tasks['4653997f-13db-4a7a-a2db-736f75b00185'] = new Task(
            '4653997f-13db-4a7a-a2db-736f75b00185',
            'title three',
            'description three',
            'ef5e8615-7b8a-4c25-9e85-b1e8241686c8',
            'new',
            new \DateTimeImmutable('2021-04-11')
        );
        $this->tasks['78cdd473-5ed7-451e-b0bf-546bd72e3b3c'] = new Task(
            '78cdd473-5ed7-451e-b0bf-546bd72e3b3c',
            'title four',
            'description four',
            '5966c003-b09b-40a3-abc7-cfcb6c31a954',
            'in_progress',
            new \DateTimeImmutable('2021-03-27')
        );
    }

}
