<?php

declare(strict_types=1);

namespace App\Common\Event;

use App\Infrastructure\Validation\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;

class ConsoleExceptionListener
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onConsoleError(ConsoleErrorEvent $event)
    {
        $exception = $event->getError();
        $output = $event->getOutput();

        if ($exception instanceof ValidationException) {
            foreach ($exception->getViolationList() as $violation) {
                $output->writeln($violation->getPropertyPath() . ' : ' . $violation->getMessage());
            }
        }
    }
}
