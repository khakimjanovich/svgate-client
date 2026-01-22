<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Validation\Attributes;

use Attribute;
use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class ArrayOf implements ValidationRule
{
    /**
     * @param  class-string  $className
     */
    public function __construct(public string $className) {}

    public function validate(mixed $value, string $name, bool $allowsNull, string $exceptionClass): void
    {
        $this->map($value, $name, $allowsNull, $exceptionClass);
    }

    /**
     * @return array<int, object>|null
     */
    public function map(mixed $value, string $name, bool $allowsNull, string $exceptionClass): ?array
    {
        if ($value === null && $allowsNull) {
            return null;
        }

        if (! is_array($value)) {
            $this->throwException($exceptionClass, $name.' must be an array.');
        }

        if (! is_subclass_of($this->className, DTOFactory::class)) {
            $this->throwException($exceptionClass, $this->className.' must implement DTOFactory.');
        }

        $mapped = [];
        foreach ($value as $item) {
            if (is_array($item)) {
                $mapped[] = $this->className::from($item);

                continue;
            }

            if ($item instanceof $this->className) {
                $mapped[] = $item;

                continue;
            }

            $this->throwException($exceptionClass, $name.' must contain arrays or '.$this->className.' instances.');
        }

        return $mapped;
    }

    private function throwException(string $exceptionClass, string $message): void
    {
        if ($exceptionClass === ResponseException::class) {
            throw new ResponseException($message, null, null, null, null, RPCErrors::SDK_RESPONSE_INVALID_ITEM);
        }

        throw new $exceptionClass($message);
    }
}
