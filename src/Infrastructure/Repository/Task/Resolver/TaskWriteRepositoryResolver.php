<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Task\Resolver;

use App\Common\Exception\ConfigurationException;
use App\Domain\Task\TaskRepositoryInterface;

class TaskWriteRepositoryResolver implements TaskWriteRepositoryResolverInterface
{
    private string $type;
    private array $repositories = [];

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function get(): TaskRepositoryInterface
    {
        foreach ($this->repositories as $repository) {
            if ($repository->supports($this->type)) {
                return $repository;
            }
        }

        throw ConfigurationException::withMessage(sprintf('Unsupported write repository type "%s"', $this->type));
    }

    public function addRepository(TaskRepositoryInterface $repository): void
    {
        $this->repositories[] = $repository;
    }
}
