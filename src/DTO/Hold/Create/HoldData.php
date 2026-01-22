<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Hold\Create;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\Attributes\PositiveInt;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class HoldData implements DTOFactory
{
    public function __construct(
        #[Length(min: 1, max: 32)]
        public string $cardId,
        #[Length(min: 1, max: 16)]
        public string $merchantId,
        #[Length(min: 1, max: 16)]
        public string $terminalId,
        #[PositiveInt]
        public int $amount,
        #[PositiveInt]
        public int $time
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        $required = ['cardId', 'merchantId', 'terminalId', 'amount', 'time'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ValidationException('holdData requires '.$field.'.');
            }
        }

        return new self(
            (string) $data['cardId'],
            (string) $data['merchantId'],
            (string) $data['terminalId'],
            (int) $data['amount'],
            (int) $data['time']
        );
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
