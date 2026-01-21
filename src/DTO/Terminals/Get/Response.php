<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Terminals\Get;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final class Response
{
    /** @var list<TerminalInfo> */
    public readonly array $terminals;

    /** @param list<TerminalInfo> $terminals */
    public function __construct(array $terminals)
    {
        $this->terminals = $terminals;
    }

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        $terminals = [];
        foreach ($data as $item) {
            if (! is_array($item)) {
                throw new ResponseException(
                    'Invalid terminal.get response item shape.',
                    $rpcId,
                    $httpStatus,
                    $rawResponse
                );
            }

            $terminals[] = TerminalInfo::fromArray($item, $rpcId, $httpStatus, $rawResponse);
        }

        return new self($terminals);
    }
}
