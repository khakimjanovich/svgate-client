<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\PayPurpose;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final readonly class TranData
{
    public function __construct(
        public string $purpose,
        public string $receiverId,
        public int $amount,
        public string $cardId,
        public int $commission,
        public string $currency,
        public string $ext,
        public string $merchantId,
        public string $terminalId,
        public ?MerchantInfo $merchantInfo = null
    ) {
        if ($this->purpose === '' || strlen($this->purpose) > 20) {
            throw new ValidationException('Purpose must be between 1 and 20 characters.');
        }

        if ($this->receiverId === '' || strlen($this->receiverId) > 250) {
            throw new ValidationException('Receiver id must be between 1 and 250 characters.');
        }

        if ($this->amount <= 0) {
            throw new ValidationException('Amount must be a positive integer.');
        }

        if ($this->cardId === '' || strlen($this->cardId) > 32) {
            throw new ValidationException('Card id must be between 1 and 32 characters.');
        }

        if ($this->commission < 0) {
            throw new ValidationException('Commission must be zero or positive.');
        }

        if ($this->currency === '' || strlen($this->currency) > 3) {
            throw new ValidationException('Currency must be between 1 and 3 characters.');
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
            'purpose' => $this->purpose,
            'receiverId' => $this->receiverId,
            'amount' => $this->amount,
            'cardId' => $this->cardId,
            'commission' => $this->commission,
            'currency' => $this->currency,
            'ext' => $this->ext,
            'merchantId' => $this->merchantId,
            'terminalId' => $this->terminalId,
        ];

        if ($this->merchantInfo !== null) {
            $payload['merchantInfo'] = $this->merchantInfo->toArray();
        }

        return $payload;
    }
}
