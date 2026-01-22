<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Internal\Redactor;

it('redacts sensitive fields recursively', function (): void {
    $redactor = new Redactor;

    $input = [
        'pan' => '8600123412345678',
        'expiry' => '2509',
        'otp' => ['code' => '123456'],
        'nested' => ['token' => 'abcd', 'other' => 'ok'],
    ];

    $output = $redactor->redactArray($input);

    expect($output['pan'])->toBe('************5678');
    expect($output['expiry'])->toBe('****');
    expect($output['otp']['code'])->toBe('**3456');
    expect($output['nested']['token'])->toBe('****');
    expect($output['nested']['other'])->toBe('ok');
});
