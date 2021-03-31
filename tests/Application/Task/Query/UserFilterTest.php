<?php

declare(strict_types=1);

namespace App\Tests\Application\Task\Query;

use App\Application\Task\Query\UserFilter;
use PHPUnit\Framework\TestCase;

class UserFilterTest extends TestCase
{
    public function testDefaultUserFilterSettings(): void
    {
        $filter = new UserFilter();

        self::assertSame(0, $filter->getOffset());
        self::assertSame(10, $filter->getLimit());
        self::assertSame([], $filter->getFields());
    }
}
