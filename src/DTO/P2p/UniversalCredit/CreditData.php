<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\UniversalCredit;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final readonly class CreditData
{
    public function __construct(
        public int $amount,
        public string $ext,
        public string $merchantId,
        public string $terminalId,
        public string $recipient,
        public SenderData $sender
    ) {
        if ($this->amount <= 0) {
            throw new ValidationException('Amount must be a positive integer.');
        }

        if ($this->ext === '' || strlen($this->ext) > 75) {
            throw new ValidationException('Ext must be between 1 and 75 characters.');
        }

        if ($this->merchantId === '' || strlen($this->merchantId) > 16) {
            throw new ValidationException('Merchant id must be between 1 and 16 characters.');
        }

        if ($this->terminalId === '' || strlen($this->terminalId) > 16) {
            throw new ValidationException('Terminal id must be between 1 and 16 characters.');
        }

        if ($this->recipient === '' || strlen($this->recipient) > 32) {
            throw new ValidationException('Recipient must be between 1 and 32 characters.');
        }
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'ext' => $this->ext,
            'merchantId' => $this->merchantId,
            'terminalId' => $this->terminalId,
            'recipient' => $this->recipient,
            'sender' => $this->sender->toArray(),
        ];
    }
}
