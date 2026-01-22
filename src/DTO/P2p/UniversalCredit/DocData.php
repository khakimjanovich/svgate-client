<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\UniversalCredit;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class DocData implements DTOFactory
{
    public function __construct(
        #[Length(min: 1, max: 3)]
        public string $nationality,
        #[Length(min: 1, max: 10)]
        public string $type,
        #[Length(min: 1, max: 25)]
        public string $seriesNumber,
        #[Length(min: 1)]
        public string $birthDate,
        #[Length(min: 1)]
        public string $validTo,
        #[Length(min: 1, max: 14)]
        public string $mrz
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        $required = ['nationality', 'type', 'seriesNumber', 'birthDate', 'validTo', 'mrz'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ValidationException('docData requires '.$field.'.');
            }
        }

        return new self(
            (string) $data['nationality'],
            (string) $data['type'],
            (string) $data['seriesNumber'],
            (string) $data['birthDate'],
            (string) $data['validTo'],
            (string) $data['mrz']
        );
    }

    public function toArray(): array
    {
        return [
            'nationality' => $this->nationality,
            'type' => $this->type,
            'seriesNumber' => $this->seriesNumber,
            'birthDate' => $this->birthDate,
            'validTo' => $this->validTo,
            'mrz' => $this->mrz,
        ];
    }
}
