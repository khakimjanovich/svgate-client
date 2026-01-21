<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Bins\List;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class Response
{
    /** @var list<BinInfo> */
    public array $bins;

    /** @param list<BinInfo> $bins */
    public function __construct(array $bins)
    {
        $this->bins = $bins;
    }

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        $bins = [];
        foreach ($data as $item) {
            if (! is_array($item)) {
                throw new ResponseException(
                    'Invalid get.bin.list response item shape.',
                    $rpcId,
                    $httpStatus,
                    $rawResponse
                );
            }

            $bins[] = BinInfo::fromArray($item, $rpcId, $httpStatus, $rawResponse);
        }

        return new self($bins);
    }
}
