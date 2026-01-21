<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\PayPurpose;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final readonly class MerchantInfo
{
    public function __construct(
        public string $mcc,
        public string $legalName,
        public int $legalType,
        public string $legalId,
        public ?string $legalOKED = null
    ) {
        if ($this->mcc === '' || strlen($this->mcc) > 4) {
            throw new ValidationException('MCC must be between 1 and 4 characters.');
        }

        if ($this->legalName === '' || strlen($this->legalName) > 80) {
            throw new ValidationException('Legal name must be between 1 and 80 characters.');
        }

        if ($this->legalType <= 0) {
            throw new ValidationException('Legal type must be a positive integer.');
        }

        if ($this->legalId === '' || strlen($this->legalId) > 14) {
            throw new ValidationException('Legal id must be between 1 and 14 characters.');
        }

        if ($this->legalOKED !== null && strlen($this->legalOKED) > 5) {
            throw new ValidationException('Legal OKED must be up to 5 characters.');
        }
    }

    public function toArray(): array
    {
        $payload = [
            'mcc' => $this->mcc,
            'legalName' => $this->legalName,
            'legalType' => $this->legalType,
            'legalId' => $this->legalId,
        ];

        if ($this->legalOKED !== null) {
            $payload['legalOKED'] = $this->legalOKED;
        }

        return $payload;
    }
}
