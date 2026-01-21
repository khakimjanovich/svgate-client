<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Info;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final class Payload
{
    public function __construct(public readonly string $hpan)
    {
        if ($this->hpan === '' || ! ctype_digit($this->hpan) || strlen($this->hpan) !== 16) {
            throw new ValidationException('HPAN must be a 16-digit string.');
        }
    }

    public function toParams(): array
    {
        return ['hpan' => $this->hpan];
    }
}
