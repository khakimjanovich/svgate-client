<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewOTP;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final readonly class CardData
{
    public function __construct(
        public string $pan,
        public string $expiry
    ) {
        if ($this->pan === '' || ! ctype_digit($this->pan) || strlen($this->pan) !== 16) {
            throw new ValidationException('Card PAN must be a 16-digit string.');
        }

        if ($this->expiry === '' || ! ctype_digit($this->expiry) || strlen($this->expiry) !== 4) {
            throw new ValidationException('Card expiry must be a 4-digit string in YYMM format.');
        }
    }

    public function toArray(): array
    {
        return [
            'pan' => $this->pan,
            'expiry' => $this->expiry,
        ];
    }
}
