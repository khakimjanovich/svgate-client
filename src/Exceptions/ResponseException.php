<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Exceptions;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use RuntimeException;

final class ResponseException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int|string|null $rpcId = null,
        public readonly ?int $httpStatus = null,
        public readonly ?string $rawResponse = null,
        ?\Throwable $previous = null,
        int $code = RPCErrors::SDK_RESPONSE
    ) {
        parent::__construct($message, $code, $previous);
    }
}
