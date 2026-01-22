<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Validation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class PositiveInt implements ValidationRule
{
    public function validate(mixed $value, string $name, bool $allowsNull, string $exceptionClass): void
    {
        if ($value === null && $allowsNull) {
            return;
        }

        if (! is_int($value) || $value <= 0) {
            throw new $exceptionClass($name.' must be a positive integer.');
        }
    }
}
