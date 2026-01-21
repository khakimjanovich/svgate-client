<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\PayPurpose;

final class Payload
{
    public function __construct(public readonly TranData $tran) {}

    public function toParams(): array
    {
        return ['tran' => $this->tran->toArray()];
    }
}
