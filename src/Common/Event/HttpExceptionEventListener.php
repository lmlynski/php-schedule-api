<?php

declare(strict_types=1);

namespace App\Common\Event;

use App\Common\Exception\NotFoundException;
use App\Common\Response\Resolver\ErrorResponseBuilderResolverInterface;
use App\Infrastructure\Validation\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        if ($response->getStatusCode() === JsonResponse::HTTP_INTERNAL_SERVER_ERROR) {
            $this->logger->error($exception->getMessage());
        }
        $event->setResponse($response);
//
//
//        if ($exception instanceof ValidationException) {
//            $body = [];
//            $body['errorMessage'] = $exception->getMessage();
//            foreach ($exception->getViolationList() as $violation) {
//                $body['errors'][] = [
//                    'field' => $violation->getPropertyPath(),
//                    'message' => $violation->getMessage()
//                ];
//            }
//
//            $response = new JsonResponse($body, JsonResponse::HTTP_BAD_REQUEST);
//        } elseif ($exception instanceof NotFoundException) {
//            $body = [];
//            $body['errorMessage'] = $exception->getMessage();
//
//            $response = new JsonResponse($body, JsonResponse::HTTP_NOT_FOUND);
//        } else {
//            $this->logger->error($exception->getMessage());
//            $response = new JsonResponse(
//                ['errorMessage' => 'Internal server error'],
//                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
//            );
//        }
//
//        $event->setResponse($response);
    }
}
