<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Internal;

/**
 * @internal
 */
final readonly class JsonRpcResult
{
    public function __construct(
        public array $result,
        public ?int $rpcId,
        public ?int $httpStatus,
        public ?string $rawResponse
    ) {}
}
