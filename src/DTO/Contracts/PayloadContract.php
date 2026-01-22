<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Contracts;

interface PayloadContract extends DTOFactory
{
    public function method(): string;
}
