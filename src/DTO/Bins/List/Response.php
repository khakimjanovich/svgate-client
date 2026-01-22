<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Bins\List;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayOf;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Response implements DTOFactory
{
    /** @param array<int, BinInfo> $bins */
    public function __construct(
        #[ArrayOf(BinInfo::class)]
        public array $bins
    ) {}

    public static function from(array $data): static
    {
        $mapped = AttributeValidator::normalize(self::class, ['bins' => $data], ResponseException::class);

        return new self($mapped['bins']);
    }
}
