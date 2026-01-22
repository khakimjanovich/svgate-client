<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Exceptions\RPCException;

it('renders to string format', function (): void {
    $exception = new RPCException(
        'SVGate API error: Card not found!',
        10,
        -200,
        'Card not found!',
        400,
        '{"error":{"code":-200,"message":"Card not found!"}}'
    );

    expect((string) $exception)->toBe(
        'SVGate RPCException(code=-200, message="Card not found!", rpc_id=10, http_status=400)'
    );
});
