<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Validation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class Digits implements ValidationRule
{
    public function __construct(
        public ?int $length = null,
        public ?int $minLength = null,
        public ?int $maxLength = null
    ) {}

    public function validate(mixed $value, string $name, bool $allowsNull, string $exceptionClass): void
    {
        if ($value === null && $allowsNull) {
            return;
        }

        if (! is_string($value)) {
            throw new $exceptionClass($name.' must be a numeric string.');
        }

        if (! ctype_digit($value)) {
            throw new $exceptionClass($name.' must contain digits only.');
        }

        $length = strlen($value);
        if ($this->length !== null && $length !== $this->length) {
            throw new $exceptionClass($name.' must be '.$this->length.' digits.');
        }

        if ($this->minLength !== null && $length < $this->minLength) {
            throw new $exceptionClass($name.' must be at least '.$this->minLength.' digits.');
        }

        if ($this->maxLength !== null && $length > $this->maxLength) {
            throw new $exceptionClass($name.' must be at most '.$this->maxLength.' digits.');
        }
    }
}
