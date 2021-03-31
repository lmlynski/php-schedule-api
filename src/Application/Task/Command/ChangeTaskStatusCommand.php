<?php

declare(strict_types=1);

namespace App\Application\Task\Command;

use App\Application\CommandInterface;

class ChangeTaskStatusCommand implements CommandInterface
{
    private string $guid;
    private string $status;

    public function __construct(string $guid, string $status)
    {
        $this->guid = $guid;
        $this->status = $status;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
