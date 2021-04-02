<?php

declare(strict_types=1);

namespace App\Tests\Application\Task\Command;

use App\Application\Task\Command\AddTaskCommand;
use App\Application\Task\Command\AddTaskCommandHandler;
use App\Domain\Task\Task;
use App\Domain\Task\TaskRepositoryInterface;
use App\Infrastructure\Repository\Task\Resolver\TaskWriteRepositoryResolverInterface;
use PHPUnit\Framework\TestCase;

class AddTaskCommandHandlerTest extends TestCase
{
    public function testHandleWillAddNewTask(): void
    {
        $taskWriteRepository = $this->createMock(TaskRepositoryInterface::class);
        $taskWriteRepository
            ->expects(self::once())
            ->method('add')
            ->with(self::isInstanceOf(Task::class));

        $taskWriteRepositoryResolver = $this->createMock(TaskWriteRepositoryResolverInterface::class);
        $taskWriteRepositoryResolver
            ->expects(self::once())
            ->method('get')
            ->willReturn($taskWriteRepository);

        $command = new AddTaskCommand(
            'some-guid',
            'some-title',
            'some-description',
            'some-assigneeId',
            'some-status',
            new \DateTimeImmutable('2021-02-02')
        );

        $handler = new AddTaskCommandHandler($taskWriteRepositoryResolver);
        $handler->handle($command);
    }
}
