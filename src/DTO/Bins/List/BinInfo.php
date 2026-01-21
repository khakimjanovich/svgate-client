<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Bins\List;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class BinInfo
{
    public function __construct(
        public string $instId,
        public string $bin
    ) {}

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        foreach (['instId', 'bin'] as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in get.bin.list response item: '.$field,
                    $rpcId,
                    $httpStatus,
                    $rawResponse
                );
            }
        }

        return new self(
            (string) $data['instId'],
            (string) $data['bin']
        );
    }
}
