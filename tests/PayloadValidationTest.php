<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Tests;

use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;

final class PayloadValidationTest extends TestCase
{
    public function test_invalid_pan_throws_payload_validation_exception(): void
    {
        $this->expectException(ValidationException::class);

        new CardData('1234', '2605');
    }
}
