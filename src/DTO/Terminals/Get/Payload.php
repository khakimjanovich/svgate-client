<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Terminals\Get;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;

final readonly class Payload implements PayloadContract
{
    public static function from(array $data): static
    {
        return new self;
    }

    public function method(): string
    {
        return 'terminal.get';
    }

    public function toParams(): array
    {
        return [];
    }
}
