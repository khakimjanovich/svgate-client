<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Exceptions;

use RuntimeException;

final class RPCException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int|string|null $rpcId,
        public readonly int $errorCode,
        public readonly ?string $errorMessage,
        public readonly ?int $httpStatus,
        public readonly ?string $rawResponse,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $errorCode, $previous);
    }

    public function __toString(): string
    {
        $rpcId = $this->rpcId === null ? 'null' : (string) $this->rpcId;
        $httpStatus = $this->httpStatus === null ? 'null' : (string) $this->httpStatus;
        $message = $this->errorMessage ?? $this->message;
        $message = str_replace('"', "'", $message);

        return sprintf(
            'SVGate RPCException(code=%d, message="%s", rpc_id=%s, http_status=%s)',
            $this->errorCode,
            $message,
            $rpcId,
            $httpStatus
        );
    }
}
