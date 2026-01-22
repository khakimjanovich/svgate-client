<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewOTP;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\IntRange;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class SmsData implements DTOFactory
{
    public function __construct(
        #[Length(max: 160)]
        public ?string $ussd = null,
        #[Length(max: 12)]
        public ?string $hash = null,
        #[IntRange(min: 0, max: 9999)]
        public ?int $templateId = null
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self(
            $data['ussd'] ?? null,
            $data['hash'] ?? null,
            $data['templateId'] ?? null
        );
    }

    public function toArray(): array
    {
        $payload = [];
        if ($this->ussd !== null) {
            $payload['ussd'] = $this->ussd;
        }
        if ($this->hash !== null) {
            $payload['hash'] = $this->hash;
        }
        if ($this->templateId !== null) {
            $payload['templateId'] = $this->templateId;
        }

        return $payload;
    }
}
