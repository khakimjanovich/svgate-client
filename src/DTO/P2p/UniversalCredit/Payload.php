<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\UniversalCredit;

final class Payload
{
    public function __construct(public readonly CreditData $credit) {}

    public function toParams(): array
    {
        return ['credit' => $this->credit->toArray()];
    }
}
