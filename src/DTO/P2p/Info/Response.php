<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Info;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final class Response
{
    public function __construct(
        public readonly string $crefNo,
        public readonly string $embosName,
        public readonly string $cardType,
        public readonly string $cardStatus,
        public readonly string $cardId,
        public readonly int $pinCnt
    ) {}

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        $required = ['CREF_NO', 'EMBOS_NAME', 'CARDTYPE', 'CARDSTATUS', 'CARDID', 'PINCNT'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in p2p.info response: '.$field,
                    $rpcId,
                    $httpStatus,
                    $rawResponse
                );
            }
        }

        return new self(
            (string) $data['CREF_NO'],
            (string) $data['EMBOS_NAME'],
            (string) $data['CARDTYPE'],
            (string) $data['CARDSTATUS'],
            (string) $data['CARDID'],
            (int) $data['PINCNT']
        );
    }
}
