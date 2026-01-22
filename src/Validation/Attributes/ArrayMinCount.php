<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Validation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class ArrayMinCount implements ValidationRule
{
    public function __construct(public int $min) {}

    public function validate(mixed $value, string $name, bool $allowsNull, string $exceptionClass): void
    {
        if ($value === null && $allowsNull) {
            return;
        }

        if (! is_array($value) || count($value) < $this->min) {
            throw new $exceptionClass($name.' must contain at least '.$this->min.' item(s).');
        }
    }
}
