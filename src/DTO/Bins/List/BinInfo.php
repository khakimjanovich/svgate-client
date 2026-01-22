<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Bins\List;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class BinInfo implements DTOFactory
{
    public function __construct(
        public string $instId,
        public string $bin
    ) {}

    public static function from(array $data): static
    {
        return new self(
            (string) $data['instId'],
            (string) $data['bin']
        );
    }

    /**
     * @return list<BinInfo>
     */
    public static function collect(array $items): array
    {
        $bins = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                throw new ResponseException(
                    'Invalid get.bin.list response item shape.',
                    null,
                    null,
                    null,
                    null,
                    RPCErrors::SDK_RESPONSE_INVALID_ITEM
                );
            }

            $bins[] = self::from($item);
        }

        return $bins;
    }
}
