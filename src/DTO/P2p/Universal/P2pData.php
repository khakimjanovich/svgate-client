<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Universal;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\Attributes\NonNegativeInt;
use Khakimjanovich\SVGate\Validation\Attributes\PositiveInt;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class P2pData implements DTOFactory
{
    public function __construct(
        #[Length(min: 1, max: 32)]
        public string $sender,
        #[Length(min: 1, max: 32)]
        public string $recipient,
        #[PositiveInt]
        public int $amount,
        #[Length(min: 1, max: 75)]
        public string $ext,
        #[Length(min: 1, max: 16)]
        public string $merchantId,
        #[Length(min: 1, max: 16)]
        public string $terminalId,
        #[NonNegativeInt]
        public ?int $feeAmount = null
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        $required = ['sender', 'recipient', 'amount', 'ext', 'merchantId', 'terminalId'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ValidationException('p2p.universal payload requires '.$field.'.');
            }
        }

        return new self(
            (string) $data['sender'],
            (string) $data['recipient'],
            (int) $data['amount'],
            (string) $data['ext'],
            (string) $data['merchantId'],
            (string) $data['terminalId'],
            isset($data['feeAmount']) ? (int) $data['feeAmount'] : null
        );
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
