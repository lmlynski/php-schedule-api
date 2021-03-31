<?php

declare(strict_types=1);

namespace App\Common\Response;

use App\Common\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class NotFoundErrorResponseBuilder implements ErrorResponseBuilderInterface
{
    private const KEY_ERROR_MESSAGE = 'errorMessage';

    public function supports(Throwable $throwable): bool
    {
        return $throwable instanceof NotFoundException;
    }

    public function build(Throwable $throwable): JsonResponse
    {
        return new JsonResponse(
            [
                self::KEY_ERROR_MESSAGE => $throwable->getMessage()
            ],
            JsonResponse::HTTP_NOT_FOUND
        );
    }

}
