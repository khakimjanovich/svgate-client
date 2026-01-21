<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\Sv;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final class Payload
{
    public function __construct(public readonly string $svId)
    {
        if ($this->svId === '' || strlen($this->svId) > 12) {
            throw new ValidationException('SV id must be between 1 and 12 characters.');
        }
    }

    public function toParams(): array
    {
        return ['svId' => $this->svId];
    }
}
