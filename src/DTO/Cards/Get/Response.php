<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\Get;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final class Response
{
    /** @var list<CardInfo> */
    public readonly array $cards;

    /** @param list<CardInfo> $cards */
    public function __construct(array $cards)
    {
        $this->cards = $cards;
    }

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        $cards = [];
        foreach ($data as $item) {
            if (! is_array($item)) {
                throw new ResponseException(
                    'Invalid cards.get response item shape.',
                    $rpcId,
                    $httpStatus,
                    $rawResponse
                );
            }

            $cards[] = CardInfo::fromArray($item, $rpcId, $httpStatus, $rawResponse);
        }

        return new self($cards);
    }
}
