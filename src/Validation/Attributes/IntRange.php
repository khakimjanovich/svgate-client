<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Validation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class IntRange implements ValidationRule
{
    public function __construct(
        public int $min,
        public int $max
    ) {}

    public function validate(mixed $value, string $name, bool $allowsNull, string $exceptionClass): void
    {
        if ($value === null && $allowsNull) {
            return;
        }

        if (! is_int($value) || $value < $this->min || $value > $this->max) {
            throw new $exceptionClass($name.' must be between '.$this->min.' and '.$this->max.'.');
        }
    }
}
