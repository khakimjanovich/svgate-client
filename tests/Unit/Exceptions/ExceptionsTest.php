<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Exceptions\TransportException;

it('response exception exposes metadata', function (): void {
    $exception = new ResponseException('bad', 10, 400, 'raw');

    expect($exception->rpcId)->toBe(10);
    expect($exception->httpStatus)->toBe(400);
    expect($exception->rawResponse)->toBe('raw');
});

it('transport exception exposes metadata', function (): void {
    $exception = new TransportException('bad', 500, 'raw');

    expect($exception->httpStatus)->toBe(500);
    expect($exception->rawResponse)->toBe('raw');
});
