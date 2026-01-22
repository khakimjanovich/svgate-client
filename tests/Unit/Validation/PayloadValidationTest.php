<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\Exceptions\ValidationException;

it('invalid pan throws validation exception', function (): void {
    expect(fn () => new CardData('1234', '2605'))
        ->toThrow(ValidationException::class);
});
