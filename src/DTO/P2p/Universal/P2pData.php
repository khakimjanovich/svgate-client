<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Universal;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final readonly class P2pData
{
    public function __construct(
        public string $sender,
        public string $recipient,
        public int $amount,
        public string $ext,
        public string $merchantId,
        public string $terminalId,
        public ?int $feeAmount = null
    ) {
        if ($this->sender === '' || strlen($this->sender) > 32) {
            throw new ValidationException('Sender must be a non-empty string up to 32 characters.');
        }

        if ($this->recipient === '' || strlen($this->recipient) > 32) {
            throw new ValidationException('Recipient must be a non-empty string up to 32 characters.');
        }

        if ($this->amount <= 0) {
            throw new ValidationException('Amount must be a positive integer.');
        }

        if ($this->feeAmount !== null && $this->feeAmount < 0) {
            throw new ValidationException('Fee amount must be zero or positive.');
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
    }

    public function toArray(): array
    {
        $payload = [
            'sender' => $this->sender,
            'recipient' => $this->recipient,
            'amount' => $this->amount,
            'ext' => $this->ext,
            'merchantId' => $this->merchantId,
            'terminalId' => $this->terminalId,
        ];

        if ($this->feeAmount !== null) {
            $payload['feeAmount'] = $this->feeAmount;
        }

        return $payload;
    }
}
