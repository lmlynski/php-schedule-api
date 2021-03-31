<?php

declare(strict_types=1);

namespace App\Common\Response\Resolver;

use App\Common\Exception\ConfigurationException;
use App\Common\Response\ErrorResponseBuilderInterface;
use Throwable;

class ErrorResponseBuilderResolver implements ErrorResponseBuilderResolverInterface
{
    private array $builders = [];

    public function get(Throwable $throwable): ErrorResponseBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($throwable)) {
                return $builder;
            }
        }

        throw ConfigurationException::withMessage(sprintf('Wrong error response builders configuration'));
    }

    public function addBuilder(ErrorResponseBuilderInterface $errorResponseBuilder): void
    {
        $this->builders[] = $errorResponseBuilder;
    }
}
