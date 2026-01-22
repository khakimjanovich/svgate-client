<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\Get;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayMinCount;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayOfStringLength;
use Khakimjanovich\SVGate\Validation\AttributeValidator;
use ReflectionException;

final readonly class Payload implements PayloadContract
{
    /** @var list<string> */
    public readonly array $ids;

    /** @param list<string> $ids
     * @throws ReflectionException
     */
    public function __construct(
        #[ArrayMinCount(1)]
        #[ArrayOfStringLength(min: 1, max: 32)]
        array $ids
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);

        $this->ids = array_values($ids);
    }

    /**
     * @throws ReflectionException
     */
    public static function from(array $data): static
    {
        return new self($data);
    }

    public function method(): string
    {
        return 'cards.get';
    }

    public function toParams(): array
    {
        return ['ids' => $this->ids];
    }
}
