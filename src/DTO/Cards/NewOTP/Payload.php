<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewOTP;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final class Payload
{
    public function __construct(
        public readonly CardData $card,
        public readonly string $serviceName,
        public readonly ?SmsData $sms = null,
        public readonly ?string $requestorPhone = null
    ) {
        if ($this->serviceName === '' || strlen($this->serviceName) > 20) {
            throw new ValidationException('Service name must be between 1 and 20 characters.');
        }

        if ($this->requestorPhone !== null) {
            if (! ctype_digit($this->requestorPhone) || strlen($this->requestorPhone) > 12) {
                throw new ValidationException('Requestor phone must be a numeric string up to 12 digits.');
            }
        }
    }

    public function toParams(): array
    {
        $params = [
            'card' => $this->card->toArray(),
            'serviceName' => $this->serviceName,
        ];

        if ($this->sms !== null) {
            $smsPayload = $this->sms->toArray();
            if ($smsPayload !== []) {
                $params['sms'] = $smsPayload;
            }
        }

        if ($this->requestorPhone !== null) {
            $params['requestorPhone'] = $this->requestorPhone;
        }

        return $params;
    }
}
