<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Task;

use App\Application\Task\Query\UserFilter;
use App\Common\Exception\ConfigurationException;
use App\Domain\Task\Task;
use App\Domain\Task\TaskRepositoryInterface;
use App\Domain\Task\Exception\TaskNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use DateTimeImmutable;

class FilesystemTaskRepository implements TaskRepositoryInterface
{
    const TYPE = 'filesystem';

    private Filesystem $filesystem;
    private string $dbFile;

    public function __construct(Filesystem $filesystem, string $dbFile)
    {
        $this->filesystem = $filesystem;
        $this->dbFile = $dbFile;
    }

    public function supports(string $type): bool
    {
        return $type === self::TYPE;
    }

    public function findByGuid(string $guid): Task
    {
        if (!$this->filesystem->exists($this->dbFile)) {
            throw new ConfigurationException(sprintf('Db file "%s" not found', $this->dbFile));
        }

        $dataAsArray = json_decode(file_get_contents($this->dbFile), true);

        $filteredArray = array_filter(
            $dataAsArray,
            function ($element) use ($guid) {
                return $element['guid'] === $guid;
            }
        );

        if (empty($filteredArray)) {
            throw TaskNotFoundException::forGuid($guid);
        }

        $result = reset($filteredArray);

        return new Task(
            $result['guid'],
            $result['title'],
            $result['description'],
            $result['assigneeId'],
            $result['status'],
            new DateTimeImmutable($result['dueDate'])
        );
    }

    public function findAllBy(UserFilter $filter): array
    {
        if (!$this->filesystem->exists($this->dbFile)) {
            throw new ConfigurationException(sprintf('Db file "%s" not found', $this->dbFile));
        }

        $dataAsArray = json_decode(file_get_contents($this->dbFile), true);

        $filteredArray = array_filter(
            $dataAsArray,
            function ($element) use ($filter) {
                if (empty($filter->getFields())) {
                    return false;
                }

                foreach ($filter->getFields() as $key => $field) {
                    if ($element[$key] !== $field) {
                        return false;
                    }
                }

                return true;
            }
        );

        $result = [];
        foreach ($filteredArray as $item) {
            $result[] = new Task(
                $item['guid'],
                $item['title'],
                $item['description'],
                $item['assigneeId'],
                $item['status'],
                new DateTimeImmutable($item['dueDate'])
            );
        }

        return array_slice($result, $filter->getOffset(), $filter->getLimit());
    }

    public function add(Task $task): void
    {
        if (!$this->filesystem->exists($this->dbFile)) {
            throw new ConfigurationException(sprintf('Db file "%s" not found', $this->dbFile));
        }

        $dataAsArray = json_decode(file_get_contents($this->dbFile), true);

        $dataAsArray[] = [
            'guid' => $task->getGuid(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'assigneeId' => $task->getAssigneeId(),
            'status' => $task->getStatus(),
            'dueDate' => $task->getDueDate()->format('Y-m-d'),
        ];

        file_put_contents($this->dbFile, json_encode($dataAsArray), JSON_PRETTY_PRINT);
    }

    public function save(Task $task): void
    {
        if (!$this->filesystem->exists($this->dbFile)) {
            throw new ConfigurationException(sprintf('Db file "%s" not found', $this->dbFile));
        }

        $dataAsArray = json_decode(file_get_contents($this->dbFile), true);

        foreach ($dataAsArray as $key => $element) {
            if ($element['guid'] === $task->getGuid()) {
                $dataAsArray[$key]['title'] = $task->getTitle();
                $dataAsArray[$key]['description'] = $task->getDescription();
                $dataAsArray[$key]['assigneeId'] = $task->getAssigneeId();
                $dataAsArray[$key]['status'] = $task->getStatus();
                $dataAsArray[$key]['dueDate'] = $task->getDueDate()->format('Y-m-d');
            }
        }

        file_put_contents($this->dbFile, json_encode($dataAsArray), JSON_PRETTY_PRINT);
    }
}
