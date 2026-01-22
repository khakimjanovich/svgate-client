<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\PayPurpose;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\Attributes\NonNegativeInt;
use Khakimjanovich\SVGate\Validation\Attributes\PositiveInt;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class TranData implements DTOFactory
{
    public function __construct(
        #[Length(min: 1, max: 20)]
        public string $purpose,
        #[Length(min: 1, max: 250)]
        public string $receiverId,
        #[PositiveInt]
        public int $amount,
        #[Length(min: 1, max: 32)]
        public string $cardId,
        #[NonNegativeInt]
        public int $commission,
        #[Length(min: 1, max: 3)]
        public string $currency,
        #[Length(min: 1, max: 75)]
        public string $ext,
        #[Length(min: 1, max: 16)]
        public string $merchantId,
        #[Length(min: 1, max: 16)]
        public string $terminalId,
        public ?MerchantInfo $merchantInfo = null
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        $required = [
            'purpose',
            'receiverId',
            'amount',
            'cardId',
            'commission',
            'currency',
            'ext',
            'merchantId',
            'terminalId',
        ];

        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ValidationException('tranData requires '.$field.'.');
            }
        }

        $merchantInfo = null;
        if (array_key_exists('merchantInfo', $data)) {
            $merchantInfo = is_array($data['merchantInfo']) ? MerchantInfo::from($data['merchantInfo']) : $data['merchantInfo'];
        }

        return new self(
            (string) $data['purpose'],
            (string) $data['receiverId'],
            (int) $data['amount'],
            (string) $data['cardId'],
            (int) $data['commission'],
            (string) $data['currency'],
            (string) $data['ext'],
            (string) $data['merchantId'],
            (string) $data['terminalId'],
            $merchantInfo
        );
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
