<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayOf;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

it('array of rejects non-array values', function (): void {
    expect(fn () => new ArrayOfLooseDto('nope'))
        ->toThrow(ValidationException::class);
});

it('array of rejects invalid item types', function (): void {
    expect(fn () => new ArrayOfChildDto([1]))
        ->toThrow(ValidationException::class);
});

it('array of rejects non-dto class mapping', function (): void {
    expect(fn () => new ArrayOfNonDto([['value' => 'a']]))
        ->toThrow(ValidationException::class);
});

final readonly class ArrayOfChildDto implements DTOFactory
{
    /** @param array<int, mixed> $children */
    public function __construct(
        #[ArrayOf(ChildDtoForArrayOf::class)]
        public array $children
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['children']);
    }
}

final readonly class ArrayOfLooseDto implements DTOFactory
{
    public function __construct(
        #[ArrayOf(ChildDtoForArrayOf::class)]
        public mixed $children
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['children']);
    }
}

final readonly class ChildDtoForArrayOf implements DTOFactory
{
    public function __construct(public string $value) {}

    public static function from(array $data): static
    {
        return new self((string) $data['value']);
    }
}

final readonly class ArrayOfNonDto implements DTOFactory
{
    /** @param array<int, mixed> $items */
    public function __construct(
        #[ArrayOf(NonDto::class)]
        public array $items
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['items']);
    }
}

final class NonDto {}
