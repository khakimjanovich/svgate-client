<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Universal;

final class Payload
{
    public function __construct(public readonly P2pData $p2p) {}

    public function toParams(): array
    {
        return ['p2p' => $this->p2p->toArray()];
    }
}
