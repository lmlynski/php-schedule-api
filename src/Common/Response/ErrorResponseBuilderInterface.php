<?php

declare(strict_types=1);

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

interface ErrorResponseBuilderInterface
{
    public function supports(Throwable $throwable): bool;

    public function build(Throwable $throwable): JsonResponse;
}
