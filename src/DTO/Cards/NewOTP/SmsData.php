<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewOTP;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final class SmsData
{
    public function __construct(
        public readonly ?string $ussd = null,
        public readonly ?string $hash = null,
        public readonly ?int $templateId = null
    ) {
        if ($this->ussd !== null && strlen($this->ussd) > 160) {
            throw new ValidationException('SMS USSD must be at most 160 characters.');
        }

        if ($this->hash !== null && strlen($this->hash) > 12) {
            throw new ValidationException('SMS hash must be at most 12 characters.');
        }

        if ($this->templateId !== null && ($this->templateId < 0 || $this->templateId > 9999)) {
            throw new ValidationException('SMS templateId must be between 0 and 9999.');
        }
    }

    public function toArray(): array
    {
        $payload = [];
        if ($this->ussd !== null) {
            $payload['ussd'] = $this->ussd;
        }
        if ($this->hash !== null) {
            $payload['hash'] = $this->hash;
        }
        if ($this->templateId !== null) {
            $payload['templateId'] = $this->templateId;
        }

        return $payload;
    }
}
