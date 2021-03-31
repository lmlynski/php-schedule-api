<?php

declare(strict_types=1);

namespace App\Common\Response\Resolver;

use App\Common\Response\ErrorResponseBuilderInterface;
use Throwable;

interface ErrorResponseBuilderResolverInterface
{
    public function get(Throwable $throwable): ErrorResponseBuilderInterface;

    public function addBuilder(ErrorResponseBuilderInterface $errorResponseBuilder): void;
}
