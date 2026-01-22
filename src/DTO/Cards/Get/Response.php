<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\Get;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayOf;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Response implements DTOFactory
{
    /** @param list<CardInfo> $cards */
    public function __construct(
        #[ArrayOf(CardInfo::class)]
        public array $cards
    ) {}

    public static function from(array $data): static
    {
        $mapped = AttributeValidator::normalize(self::class, ['cards' => $data], ResponseException::class);

        return new self($mapped['cards']);
    }
}
