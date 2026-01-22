<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\UniversalCredit;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class SenderData implements DTOFactory
{
    public function __construct(
        #[Length(min: 1, max: 32)]
        public string $id,
        #[Length(min: 1, max: 80)]
        public string $legalName,
        #[Length(min: 1, max: 80)]
        public string $system,
        #[Length(min: 1, max: 50)]
        public string $lastName,
        #[Length(min: 1, max: 50)]
        public string $firstName,
        #[Length(min: 1, max: 50)]
        public string $middleName,
        #[Length(min: 1, max: 12)]
        public string $refNum,
        public ?DocData $doc = null
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        $required = ['id', 'legalName', 'system', 'lastName', 'firstName', 'middleName', 'refNum'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ValidationException('senderData requires '.$field.'.');
            }
        }

        $doc = null;
        if (array_key_exists('doc', $data)) {
            $doc = is_array($data['doc']) ? DocData::from($data['doc']) : $data['doc'];
        }

        return new self(
            (string) $data['id'],
            (string) $data['legalName'],
            (string) $data['system'],
            (string) $data['lastName'],
            (string) $data['firstName'],
            (string) $data['middleName'],
            (string) $data['refNum'],
            $doc
        );
    }

    public function toArray(): array
    {
        $payload = [
            'id' => $this->id,
            'legalName' => $this->legalName,
            'system' => $this->system,
            'lastName' => $this->lastName,
            'firstName' => $this->firstName,
            'middleName' => $this->middleName,
            'refNum' => $this->refNum,
        ];

        if ($this->doc !== null) {
            $payload['doc'] = $this->doc->toArray();
        }

        return $payload;
    }
}
