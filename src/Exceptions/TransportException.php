<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Exceptions;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use RuntimeException;

final class TransportException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?int $httpStatus = null,
        public readonly ?string $rawResponse = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, RPCErrors::SDK_TRANSPORT, $previous);
    }
}
