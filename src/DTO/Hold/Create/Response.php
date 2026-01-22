<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Hold\Create;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class Response implements DTOFactory
{
    public function __construct(
        public readonly int $id,
        public readonly int $status,
        public readonly string $description
    ) {}

    public static function from(array $data): static
    {
        foreach (['id', 'status', 'description'] as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in hold.create response: '.$field,
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
            (int) $data['status'],
            (string) $data['description']
        );
    }
}
