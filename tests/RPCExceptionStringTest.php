<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Tests;

use Khakimjanovich\SVGate\Exceptions\RPCException;
use PHPUnit\Framework\TestCase;

final class RPCExceptionStringTest extends TestCase
{
    public function test_to_string_format(): void
    {
        $exception = new RPCException(
            'SVGate API error: Card not found!',
            10,
            -200,
            'Card not found!',
            400,
            '{"error":{"code":-200,"message":"Card not found!"}}'
        );

        $this->assertSame(
            'SVGate RPCException(code=-200, message="Card not found!", rpc_id=10, http_status=400)',
            (string) $exception
        );
    }
}
