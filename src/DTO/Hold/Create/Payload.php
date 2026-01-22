<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Hold\Create;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Payload implements PayloadContract
{
    public function __construct(public HoldData $hold)
    {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        if (! array_key_exists('hold', $data)) {
            throw new ValidationException('hold.create payload requires hold.');
        }

        $hold = is_array($data['hold']) ? HoldData::from($data['hold']) : $data['hold'];

        return new self($hold);
    }

    public function method(): string
    {
        return 'hold.create';
    }

    public function toParams(): array
    {
        return ['hold' => $this->hold->toArray()];
    }
}
