<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\PayPurpose;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Payload implements PayloadContract
{
    public function __construct(public TranData $tran)
    {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        if (! array_key_exists('tran', $data)) {
            throw new ValidationException('trans.pay.purpose payload requires tran.');
        }

        $tran = is_array($data['tran']) ? TranData::from($data['tran']) : $data['tran'];

        return new self($tran);
    }

    public function method(): string
    {
        return 'trans.pay.purpose';
    }

    public function toParams(): array
    {
        return ['tran' => $this->tran->toArray()];
    }
}
