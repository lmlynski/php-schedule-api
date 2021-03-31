<?php

declare(strict_types=1);

namespace App\Common\Exception;

use Exception;

class ConfigurationException extends Exception
{
    public static function withMessage(string $message): self
    {
        return new static($message);
    }
}
