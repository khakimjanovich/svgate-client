<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Terminals\Get;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayOf;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Response implements DTOFactory
{
    /** @var list<TerminalInfo> */
    public readonly array $terminals;

    /** @param list<TerminalInfo> $terminals */
    public function __construct(
        #[ArrayOf(TerminalInfo::class)]
        array $terminals
    ) {
        $this->terminals = $terminals;
    }

    public static function from(array $data): static
    {
        $mapped = AttributeValidator::normalize(self::class, ['terminals' => $data], ResponseException::class);

        return new self($mapped['terminals']);
    }
}
