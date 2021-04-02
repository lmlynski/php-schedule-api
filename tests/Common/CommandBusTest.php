<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Application\CommandInterface;
use App\Application\CommandHandlerInterface;
use App\Application\Task\Command\AddTaskCommand;
use App\Common\CommandBus;
use App\Common\Exception\ConfigurationException;
use PHPUnit\Framework\TestCase;

class CommandBusTest extends TestCase
{
    public function testDispatchWithNoHandlersRegisteredWillThrowConfigurationException(): void
    {
        self::expectException(ConfigurationException::class);

        (new CommandBus())->dispatch($this->createMock(CommandInterface::class));
    }

    public function testDispatchWithHandlerRegisteredWillExecuteRegisteredHandler(): void
    {
        $command = new AddTaskCommand(
            'some-guid',
            'some-title',
            'some-description',
            'some-assigneeId',
            'some-status',
            new \DateTimeImmutable('2021-02-02')
        );

        $commandHandler = $this->createMock(CommandHandlerInterface::class);
        $commandHandler
            ->expects(self::once())
            ->method('handle')
            ->with($command);

        $commandBus = new CommandBus();
        $commandBus->registerHandler(get_class($command), $commandHandler);
        $commandBus->dispatch($command);
    }
}
