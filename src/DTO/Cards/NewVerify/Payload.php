<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewVerify;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Payload implements PayloadContract
{
    public function __construct(public OtpData $otp)
    {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        if (! array_key_exists('otp', $data)) {
            throw new ValidationException('cards.new.verify payload requires otp.');
        }

        $otp = is_array($data['otp']) ? OtpData::from($data['otp']) : $data['otp'];

        return new self($otp);
    }

    public function method(): string
    {
        return 'cards.new.verify';
    }

    public function toParams(): array
    {
        return [
            'otp' => $this->otp->toArray(),
        ];
    }
}
