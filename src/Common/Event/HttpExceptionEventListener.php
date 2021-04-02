<?php

declare(strict_types=1);

namespace App\Common\Event;

use App\Common\Response\Resolver\ErrorResponseBuilderResolverInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class HttpExceptionEventListener
{
    private ErrorResponseBuilderResolverInterface $errorResponseBuilderResolver;
    private LoggerInterface $logger;

    public function __construct(
        ErrorResponseBuilderResolverInterface $errorResponseBuilderResolver,
        LoggerInterface $logger
    ) {
        $this->errorResponseBuilderResolver = $errorResponseBuilderResolver;
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $event->allowCustomResponseCode();
        $exception = $event->getThrowable();

        $response = $this->errorResponseBuilderResolver->get($exception)->build($exception);
        if ($response->isServerError()) {
            $this->logger->error($exception->getMessage());
        }

        $event->setResponse($response);
    }
}
