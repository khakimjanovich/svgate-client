<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewVerify;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final readonly class OtpData
{
    public function __construct(
        public int $id,
        public string $code
    ) {
        if ($this->id <= 0) {
            throw new ValidationException('OTP id must be a positive integer.');
        }

        if ($this->code === '' || ! ctype_digit($this->code) || strlen($this->code) !== 6) {
            throw new ValidationException('OTP code must be a 6-digit string.');
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
        ];
    }
}
