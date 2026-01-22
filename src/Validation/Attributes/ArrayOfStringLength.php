<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Validation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class ArrayOfStringLength implements ValidationRule
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

        if (! is_array($value)) {
            throw new $exceptionClass($name.' must be an array of strings.');
        }

        foreach ($value as $item) {
            if (! is_string($item)) {
                throw new $exceptionClass($name.' must contain only strings.');
            }

            $length = strlen($item);
            if ($this->min !== null && $length < $this->min) {
                throw new $exceptionClass($name.' items must be at least '.$this->min.' characters.');
            }

            if ($this->max !== null && $length > $this->max) {
                throw new $exceptionClass($name.' items must be at most '.$this->max.' characters.');
            }
        }
    }
}
