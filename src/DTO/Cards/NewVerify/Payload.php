<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewVerify;

final class Payload
{
    public function __construct(public readonly OtpData $otp) {}

    public function toParams(): array
    {
        return [
            'otp' => $this->otp->toArray(),
        ];
    }
}
