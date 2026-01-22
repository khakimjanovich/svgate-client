<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Validation\Attributes;

interface ValidationRule
{
    public function validate(mixed $value, string $name, bool $allowsNull, string $exceptionClass): void;
}
