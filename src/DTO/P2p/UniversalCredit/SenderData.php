<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\UniversalCredit;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final readonly class SenderData
{
    public function __construct(
        public string $id,
        public string $legalName,
        public string $system,
        public string $lastName,
        public string $firstName,
        public string $middleName,
        public string $refNum,
        public ?DocData $doc = null
    ) {
        if ($this->id === '' || strlen($this->id) > 32) {
            throw new ValidationException('Sender id must be between 1 and 32 characters.');
        }

        if ($this->legalName === '' || strlen($this->legalName) > 80) {
            throw new ValidationException('Sender legal name must be between 1 and 80 characters.');
        }

        if ($this->system === '' || strlen($this->system) > 80) {
            throw new ValidationException('Sender system must be between 1 and 80 characters.');
        }

        if ($this->lastName === '' || strlen($this->lastName) > 50) {
            throw new ValidationException('Sender last name must be between 1 and 50 characters.');
        }

        if ($this->firstName === '' || strlen($this->firstName) > 50) {
            throw new ValidationException('Sender first name must be between 1 and 50 characters.');
        }

        if ($this->middleName === '' || strlen($this->middleName) > 50) {
            throw new ValidationException('Sender middle name must be between 1 and 50 characters.');
        }

        if ($this->refNum === '' || strlen($this->refNum) > 12) {
            throw new ValidationException('Sender refNum must be between 1 and 12 characters.');
        }
    }

    public function toArray(): array
    {
        $payload = [
            'id' => $this->id,
            'legalName' => $this->legalName,
            'system' => $this->system,
            'lastName' => $this->lastName,
            'firstName' => $this->firstName,
            'middleName' => $this->middleName,
            'refNum' => $this->refNum,
        ];

        if ($this->doc !== null) {
            $payload['doc'] = $this->doc->toArray();
        }

        return $payload;
    }
}
