<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewOTP;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class Response implements DTOFactory
{
    public function __construct(
        public readonly int $id,
        public readonly string $phoneMask,
        public readonly string $token,
        public readonly bool $verified
    ) {}

    public static function from(array $data): static
    {
        foreach (['id', 'phoneMask', 'token', 'verified'] as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in cards.new.otp response: '.$field,
                    null,
                    null,
                    null,
                    null,
                    RPCErrors::SDK_RESPONSE_MISSING_FIELD
                );
            }
        }

        return new self(
            (int) $data['id'],
            (string) $data['phoneMask'],
            (string) $data['token'],
            (bool) $data['verified']
        );
    }
}
