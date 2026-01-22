<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Info;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class Response implements DTOFactory
{
    public function __construct(
        public readonly string $crefNo,
        public readonly string $embosName,
        public readonly string $cardType,
        public readonly string $cardStatus,
        public readonly string $cardId,
        public readonly int $pinCnt
    ) {}

    public static function from(array $data): static
    {
        $required = ['CREF_NO', 'EMBOS_NAME', 'CARDTYPE', 'CARDSTATUS', 'CARDID', 'PINCNT'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in p2p.info response: '.$field,
                    null,
                    null,
                    null,
                    null,
                    RPCErrors::SDK_RESPONSE_MISSING_FIELD
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
