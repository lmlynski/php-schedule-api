<?php

declare(strict_types=1);

namespace App\Domain\Task;

use DateTimeImmutable;

class Task
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

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAssigneeId(): string
    {
        return $this->assigneeId;
    }

    public function setAssigneeId(string $assigneeId): void
    {
        $this->assigneeId = $assigneeId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getDueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function setDueDate(DateTimeImmutable $dueDate): void
    {
        $this->dueDate = $dueDate;
    }
}
