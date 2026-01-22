<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Info;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Digits;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Payload implements PayloadContract
{
    public function __construct(
        #[Digits(length: 16)]
        public string $hpan
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        if (! array_key_exists('hpan', $data)) {
            throw new ValidationException('p2p.info payload requires hpan.');
        }

        return new self((string) $data['hpan']);
    }

    public function method(): string
    {
        return 'p2p.info';
    }

    public function toParams(): array
    {
        return ['hpan' => $this->hpan];
    }
}
