<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewVerify;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Digits;
use Khakimjanovich\SVGate\Validation\Attributes\PositiveInt;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class OtpData implements DTOFactory
{
    public function __construct(
        #[PositiveInt]
        public int $id,
        #[Digits(length: 6)]
        public string $code
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        if (! array_key_exists('id', $data) || ! array_key_exists('code', $data)) {
            throw new ValidationException('OTP data requires id and code.');
        }

        return new self((int) $data['id'], (string) $data['code']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
        ];
    }
}
