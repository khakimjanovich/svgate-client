<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\PayPurpose;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\Attributes\PositiveInt;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class MerchantInfo implements DTOFactory
{
    public function __construct(
        #[Length(min: 1, max: 4)]
        public string $mcc,
        #[Length(min: 1, max: 80)]
        public string $legalName,
        #[PositiveInt]
        public int $legalType,
        #[Length(min: 1, max: 14)]
        public string $legalId,
        #[Length(max: 5)]
        public ?string $legalOKED = null
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        $required = ['mcc', 'legalName', 'legalType', 'legalId'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ValidationException('merchantInfo requires '.$field.'.');
            }
        }

        return new self(
            (string) $data['mcc'],
            (string) $data['legalName'],
            (int) $data['legalType'],
            (string) $data['legalId'],
            $data['legalOKED'] ?? null
        );
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
