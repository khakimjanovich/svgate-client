<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\UniversalCredit;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Payload implements PayloadContract
{
    public function __construct(public CreditData $credit)
    {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        if (! array_key_exists('credit', $data)) {
            throw new ValidationException('p2p.universal.credit payload requires credit.');
        }

        $credit = is_array($data['credit']) ? CreditData::from($data['credit']) : $data['credit'];

        return new self($credit);
    }

    public function method(): string
    {
        return 'p2p.universal.credit';
    }

    public function toParams(): array
    {
        return ['credit' => $this->credit->toArray()];
    }
}
