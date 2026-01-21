<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Exceptions;

use RuntimeException;

final class ResponseException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int|string|null $rpcId = null,
        public readonly ?int $httpStatus = null,
        public readonly ?string $rawResponse = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }
}
