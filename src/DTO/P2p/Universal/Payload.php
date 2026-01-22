<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Universal;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Payload implements PayloadContract
{
    public function __construct(public P2pData $p2p)
    {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        if (! array_key_exists('p2p', $data)) {
            throw new ValidationException('p2p.universal payload requires p2p.');
        }

        $p2p = is_array($data['p2p']) ? P2pData::from($data['p2p']) : $data['p2p'];

        return new self($p2p);
    }

    public function method(): string
    {
        return 'p2p.universal';
    }

    public function toParams(): array
    {
        return ['p2p' => $this->p2p->toArray()];
    }
}
