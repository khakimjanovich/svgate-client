<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Contracts;

interface DTOFactory
{
    public static function from(array $data): static;
}
