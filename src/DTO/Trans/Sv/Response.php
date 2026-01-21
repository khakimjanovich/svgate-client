<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\Sv;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class Response
{
    /** @var list<TransactionInfo> */
    public array $transactions;

    /** @param list<TransactionInfo> $transactions */
    public function __construct(array $transactions)
    {
        $this->transactions = $transactions;
    }

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        $transactions = [];
        foreach ($data as $item) {
            if (! is_array($item)) {
                throw new ResponseException(
                    'Invalid trans.sv response item shape.',
                    $rpcId,
                    $httpStatus,
                    $rawResponse
                );
            }

            $transactions[] = TransactionInfo::fromArray($item, $rpcId, $httpStatus, $rawResponse);
        }

        return new self($transactions);
    }
}
