<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Codes\RPCErrors;

it('all error codes are ints', function (): void {
    $reflection = new \ReflectionClass(RPCErrors::class);
    foreach ($reflection->getConstants() as $value) {
        if (is_array($value)) {
            expect($value)->not()->toBeEmpty();

            continue;
        }

        expect($value)->toBeInt();
    }
});

it('rpc errors resolve messages and return null for unknown codes', function (): void {
    expect(RPCErrors::message(RPCErrors::CODE_NEG_200))->toBe('Card not found!');
    expect(RPCErrors::message(123456))->toBeNull();
});
