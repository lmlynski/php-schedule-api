<?php

declare (strict_types=1);

namespace App\Application\Task\Query\Model;

class TaskView
{
    public string $guid;
    public string $title;
    public string $description;
    public string $assigneeId;
    public string $status;
    public string $dueDate;

    public function __construct(
        string $guid,
        string $title,
        string $description,
        string $assigneeId,
        string $status,
        string $dueDate
    ) {
        $this->guid = $guid;
        $this->title = $title;
        $this->description = $description;
        $this->assigneeId = $assigneeId;
        $this->status = $status;
        $this->dueDate = $dueDate;
    }
}
