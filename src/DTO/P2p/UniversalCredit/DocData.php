<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\UniversalCredit;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final readonly class DocData
{
    public function __construct(
        public string $nationality,
        public string $type,
        public string $seriesNumber,
        public string $birthDate,
        public string $validTo,
        public string $mrz
    ) {
        if ($this->nationality === '' || strlen($this->nationality) > 3) {
            throw new ValidationException('Nationality must be between 1 and 3 characters.');
        }

        if ($this->type === '' || strlen($this->type) > 10) {
            throw new ValidationException('Document type must be between 1 and 10 characters.');
        }

        if ($this->seriesNumber === '' || strlen($this->seriesNumber) > 25) {
            throw new ValidationException('Series number must be between 1 and 25 characters.');
        }

        if ($this->birthDate === '') {
            throw new ValidationException('Birth date is required.');
        }

        if ($this->validTo === '') {
            throw new ValidationException('Valid-to date is required.');
        }

        if ($this->mrz === '' || strlen($this->mrz) > 14) {
            throw new ValidationException('MRZ must be between 1 and 14 characters.');
        }
    }

    public function toArray(): array
    {
        return [
            'nationality' => $this->nationality,
            'type' => $this->type,
            'seriesNumber' => $this->seriesNumber,
            'birthDate' => $this->birthDate,
            'validTo' => $this->validTo,
            'mrz' => $this->mrz,
        ];
    }
}
