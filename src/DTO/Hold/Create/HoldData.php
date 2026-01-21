<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Hold\Create;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final readonly class HoldData
{
    public function __construct(
        public string $cardId,
        public string $merchantId,
        public string $terminalId,
        public int $amount,
        public int $time
    ) {
        if ($this->cardId === '' || strlen($this->cardId) > 32) {
            throw new ValidationException('Card id must be between 1 and 32 characters.');
        }

        if ($this->merchantId === '' || strlen($this->merchantId) > 16) {
            throw new ValidationException('Merchant id must be between 1 and 16 characters.');
        }

        if ($this->terminalId === '' || strlen($this->terminalId) > 16) {
            throw new ValidationException('Terminal id must be between 1 and 16 characters.');
        }

        if ($this->amount <= 0) {
            throw new ValidationException('Amount must be a positive integer.');
        }

        if ($this->time <= 0) {
            throw new ValidationException('Time must be a positive integer in minutes.');
        }
    }

    public function toArray(): array
    {
        return [
            'cardId' => $this->cardId,
            'merchantId' => $this->merchantId,
            'terminalId' => $this->terminalId,
            'amount' => $this->amount,
            'time' => $this->time,
        ];
    }
}
