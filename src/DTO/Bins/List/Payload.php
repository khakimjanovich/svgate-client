<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Bins\List;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;

final readonly class Payload implements PayloadContract
{
    public static function from(array $data): static
    {
        return new self;
    }

    public function method(): string
    {
        return 'get.bin.list';
    }

    public function toParams(): array
    {
        return [];
    }
}
