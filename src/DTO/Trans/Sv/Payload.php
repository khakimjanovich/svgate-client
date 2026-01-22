<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\Sv;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Payload implements PayloadContract
{
    public function __construct(
        #[Length(min: 1, max: 12)]
        public string $svId
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        if (! array_key_exists('svId', $data)) {
            throw new ValidationException('trans.sv payload requires svId.');
        }

        return new self((string) $data['svId']);
    }

    public function method(): string
    {
        return 'trans.sv';
    }

    public function toParams(): array
    {
        return ['svId' => $this->svId];
    }
}
