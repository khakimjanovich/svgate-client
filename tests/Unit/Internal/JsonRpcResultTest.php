<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Internal\JsonRpcResult;

it('result properties are exposed', function (): void {
    $result = new JsonRpcResult(['ok' => true], 10, 200, '{"ok":true}');

    expect($result->result)->toBe(['ok' => true]);
    expect($result->rpcId)->toBe(10);
    expect($result->httpStatus)->toBe(200);
    expect($result->rawResponse)->toBe('{"ok":true}');
});
