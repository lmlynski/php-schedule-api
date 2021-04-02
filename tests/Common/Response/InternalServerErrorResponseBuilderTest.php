<?php

declare(strict_types=1);

namespace App\Tests\Common\Response;

use App\Common\Response\InternalServerErrorResponseBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class InternalServerErrorResponseBuilderTest extends TestCase
{
    public function testBuildWillReturnJsonResponseWithSpecifiedMessage(): void
    {
        $builder = new InternalServerErrorResponseBuilder();
        $result = $builder->build(new Exception('Some error message.'));

        self::assertInstanceOf(JsonResponse::class, $result);
        self::assertSame(500, $result->getStatusCode());
        self::assertIsArray(json_decode($result->getContent(), true));
        self::assertSame('Internal server error', json_decode($result->getContent(), true)['errorMessage']);
    }
}
