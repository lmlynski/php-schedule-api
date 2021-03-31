<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

use App\Application\CommandInterface;
use DateTimeImmutable;

class AddTaskCommand implements CommandInterface
{
    private string $guid;
    private string $title;
    private string $description;
    private string $assigneeId;
    private string $status;
    private DateTimeImmutable $dueDate;

    public function __construct(
        string $guid,
        string $title,
        string $description,
        string $assigneeId,
        string $status,
        DateTimeImmutable $dueDate
    ) {
        $this->guid = $guid;
        $this->title = $title;
        $this->description = $description;
        $this->assigneeId = $assigneeId;
        $this->status = $status;
        $this->dueDate = $dueDate;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAssigneeId(): string
    {
        return $this->assigneeId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getDueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }
}
