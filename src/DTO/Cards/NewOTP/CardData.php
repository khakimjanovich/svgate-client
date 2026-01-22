<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewOTP;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Digits;
use Khakimjanovich\SVGate\Validation\AttributeValidator;
use ReflectionException;

final readonly class CardData implements DTOFactory
{
    /**
     * @throws ReflectionException
     */
    public function __construct(
        #[Digits(length: 16)]
        public string $pan,
        #[Digits(length: 4)]
        public string $expiry
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    /**
     * @throws ReflectionException
     */
    public static function from(array $data): static
    {
        if (! array_key_exists('pan', $data) || ! array_key_exists('expiry', $data)) {
            throw new ValidationException('Card data requires pan and expiry.');
        }

        return new self((string) $data['pan'], (string) $data['expiry']);
    }

    public function toArray(): array
    {
        return [
            'pan' => $this->pan,
            'expiry' => $this->expiry,
        ];
    }
}
