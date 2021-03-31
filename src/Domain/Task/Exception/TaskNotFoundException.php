<?php

declare(strict_types=1);

namespace App\Domain\Task\Exception;

use App\Common\Exception\NotFoundException;

class TaskNotFoundException extends NotFoundException
{
    public static function forGuid(string $guid): self
    {
        return new static(sprintf('Task for guid "%s" not found', $guid));
    }
}
