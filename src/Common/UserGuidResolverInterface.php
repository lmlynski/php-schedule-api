<?php

declare(strict_types=1);

namespace App\Common;

interface UserGuidResolverInterface
{
    public function resolve(): string;
}
