<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Exceptions;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use InvalidArgumentException;

final class ValidationException extends InvalidArgumentException
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, RPCErrors::SDK_VALIDATION, $previous);
    }
}
