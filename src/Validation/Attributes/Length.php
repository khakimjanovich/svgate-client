<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Validation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class Length implements ValidationRule
{
    public function __construct(
        public ?int $min = null,
        public ?int $max = null
    ) {}

    public function validate(mixed $value, string $name, bool $allowsNull, string $exceptionClass): void
    {
        if ($value === null && $allowsNull) {
            return;
        }

        if (! is_string($value)) {
            throw new $exceptionClass($name.' must be a string.');
        }

        $length = strlen($value);
        if ($this->min !== null && $length < $this->min) {
            throw new $exceptionClass($name.' must be at least '.$this->min.' characters.');
        }

        if ($this->max !== null && $length > $this->max) {
            throw new $exceptionClass($name.' must be at most '.$this->max.' characters.');
        }
    }
}
