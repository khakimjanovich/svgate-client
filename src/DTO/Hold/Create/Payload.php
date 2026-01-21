<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Hold\Create;

final class Payload
{
    public function __construct(public readonly HoldData $hold) {}

    public function toParams(): array
    {
        return ['hold' => $this->hold->toArray()];
    }
}
