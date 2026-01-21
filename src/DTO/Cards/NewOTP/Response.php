<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewOTP;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final class Response
{
    public function __construct(
        public readonly int $id,
        public readonly string $phoneMask,
        public readonly string $token,
        public readonly bool $verified
    ) {}

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        foreach (['id', 'phoneMask', 'token', 'verified'] as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in cards.new.otp response: '.$field,
                    $rpcId,
                    $httpStatus,
                    $rawResponse
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
