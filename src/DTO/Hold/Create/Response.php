<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Hold\Create;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final class Response
{
    public function __construct(
        public readonly int $id,
        public readonly int $status,
        public readonly string $description
    ) {}

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        foreach (['id', 'status', 'description'] as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in hold.create response: '.$field,
                    $rpcId,
                    $httpStatus,
                    $rawResponse
                );
            }
        }

        return new self(
            (int) $data['id'],
            (int) $data['status'],
            (string) $data['description']
        );
    }
}
