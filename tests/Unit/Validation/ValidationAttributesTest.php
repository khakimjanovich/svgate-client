<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayMinCount;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayOf;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayOfStringLength;
use Khakimjanovich\SVGate\Validation\Attributes\Digits;
use Khakimjanovich\SVGate\Validation\Attributes\IntRange;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\Attributes\NonNegativeInt;
use Khakimjanovich\SVGate\Validation\Attributes\PositiveInt;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

it('length attribute rejects short string', function (): void {
    expect(fn () => new LengthDto('a'))
        ->toThrow(ValidationException::class);
});

it('length attribute rejects long string', function (): void {
    expect(fn () => new LengthDto('toolong'))
        ->toThrow(ValidationException::class);
});

it('length attribute rejects non-string values', function (): void {
    expect(fn () => new LengthMixedDto(123))
        ->toThrow(ValidationException::class);
});

it('digits attribute requires numeric string', function (): void {
    expect(fn () => new DigitsDto('12ab'))
        ->toThrow(ValidationException::class);
});

it('positive int attribute requires positive value', function (): void {
    expect(fn () => new PositiveIntDto(0))
        ->toThrow(ValidationException::class);
});

it('positive int attribute allows positive values', function (): void {
    $dto = new PositiveIntDto(5);

    expect($dto->value)->toBe(5);
});

it('positive int attribute allows null when nullable', function (): void {
    $dto = new PositiveIntNullableDto(null);

    expect($dto->value)->toBeNull();
});

it('non negative int attribute allows zero', function (): void {
    $dto = new NonNegativeIntDto(0);

    expect($dto->value)->toBe(0);
});

it('non negative int attribute rejects negative values', function (): void {
    expect(fn () => new NonNegativeIntDto(-1))
        ->toThrow(ValidationException::class);
});

it('int range attribute rejects out of range', function (): void {
    expect(fn () => new IntRangeDto(10))
        ->toThrow(ValidationException::class);
});

it('length attribute allows null when nullable', function (): void {
    $dto = new LengthNullableDto(null);

    expect($dto->value)->toBeNull();
});

it('digits attribute rejects non-string values', function (): void {
    expect(fn () => new DigitsMixedDto(1234))
        ->toThrow(ValidationException::class);
});

it('digits attribute enforces min and max length', function (): void {
    expect(fn () => new DigitsRangeDto('1'))
        ->toThrow(ValidationException::class);

    expect(fn () => new DigitsRangeDto('123456'))
        ->toThrow(ValidationException::class);
});

it('array min count attribute requires items', function (): void {
    expect(fn () => new ArrayMinCountDto([]))
        ->toThrow(ValidationException::class);
});

it('array min count attribute allows minimum size', function (): void {
    $dto = new ArrayMinCountDto(['a', 'b']);

    expect($dto->items)->toHaveCount(2);
});

it('array min count attribute allows null when nullable', function (): void {
    $dto = new ArrayMinCountNullableDto(null);

    expect($dto->items)->toBeNull();
});

it('array min count attribute rejects non-array values', function (): void {
    expect(fn () => new ArrayMinCountMixedDto('nope'))
        ->toThrow(ValidationException::class);
});

it('array of string length attribute rejects long items', function (): void {
    expect(fn () => new ArrayOfStringLengthDto(['toolong']))
        ->toThrow(ValidationException::class);
});

it('array of string length attribute rejects short items', function (): void {
    expect(fn () => new ArrayOfStringLengthDto(['']))
        ->toThrow(ValidationException::class);
});

it('array of string length attribute rejects non-array values', function (): void {
    expect(fn () => new ArrayOfStringLengthMixedDto('nope'))
        ->toThrow(ValidationException::class);
});

it('array of string length attribute rejects non-string items', function (): void {
    expect(fn () => new ArrayOfStringLengthDto(['ok', 1]))
        ->toThrow(ValidationException::class);
});

it('array of string length attribute allows null when nullable', function (): void {
    $dto = new ArrayOfStringLengthNullableDto(null);

    expect($dto->items)->toBeNull();
});

it('array of attribute maps child objects', function (): void {
    $dto = ArrayOfDto::from([
        'children' => [
            ['value' => 'a'],
            ['value' => 'b'],
        ],
    ]);

    expect($dto->children)->toHaveCount(2);
    expect($dto->children[0])->toBeInstanceOf(ChildDto::class);
    expect($dto->children[0]->value)->toBe('a');
});

it('array of attribute keeps provided child instances', function (): void {
    $child = new ChildDto('x');
    $dto = ArrayOfDto::from(['children' => [$child]]);

    expect($dto->children[0])->toBe($child);
});

it('array of attribute allows null when nullable', function (): void {
    $dto = new ArrayOfNullableDto(null);

    expect($dto->children)->toBeNull();
});

it('array of validate delegates to mapping', function (): void {
    $rule = new ArrayOf(ChildDto::class);

    expect(fn () => $rule->validate([['value' => 'a']], 'children', false, ValidationException::class))
        ->not
        ->toThrow(ValidationException::class);
});

it('attribute validator ignores missing parameters', function (): void {
    $normalized = AttributeValidator::normalize(OptionalParamDto::class, [], ValidationException::class);

    expect($normalized)->toBe([]);
});

final readonly class LengthDto implements DTOFactory
{
    public function __construct(
        #[Length(min: 2, max: 3)]
        public string $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self((string) $data['value']);
    }
}

final readonly class LengthMixedDto implements DTOFactory
{
    public function __construct(
        #[Length(min: 1, max: 3)]
        public mixed $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['value'] ?? null);
    }
}

final readonly class DigitsDto implements DTOFactory
{
    public function __construct(
        #[Digits(length: 4)]
        public string $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self((string) $data['value']);
    }
}

final readonly class PositiveIntDto implements DTOFactory
{
    public function __construct(
        #[PositiveInt]
        public int $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self((int) $data['value']);
    }
}

final readonly class PositiveIntNullableDto implements DTOFactory
{
    public function __construct(
        #[PositiveInt]
        public ?int $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['value'] ?? null);
    }
}

final readonly class NonNegativeIntDto implements DTOFactory
{
    public function __construct(
        #[NonNegativeInt]
        public int $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self((int) $data['value']);
    }
}

final readonly class IntRangeDto implements DTOFactory
{
    public function __construct(
        #[IntRange(min: 1, max: 5)]
        public int $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self((int) $data['value']);
    }
}

final readonly class LengthNullableDto implements DTOFactory
{
    public function __construct(
        #[Length(min: 2, max: 3)]
        public ?string $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['value'] ?? null);
    }
}

final readonly class DigitsMixedDto implements DTOFactory
{
    public function __construct(
        #[Digits(length: 4)]
        public mixed $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['value'] ?? null);
    }
}

final readonly class DigitsRangeDto implements DTOFactory
{
    public function __construct(
        #[Digits(minLength: 2, maxLength: 4)]
        public string $value
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self((string) $data['value']);
    }
}

final readonly class ArrayMinCountDto implements DTOFactory
{
    /** @param list<string> $items */
    public function __construct(
        #[ArrayMinCount(2)]
        public array $items
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['items']);
    }
}

final readonly class ArrayMinCountNullableDto implements DTOFactory
{
    /** @param list<string>|null $items */
    public function __construct(
        #[ArrayMinCount(2)]
        public ?array $items
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['items'] ?? null);
    }
}

final readonly class ArrayMinCountMixedDto implements DTOFactory
{
    public function __construct(
        #[ArrayMinCount(2)]
        public mixed $items
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['items'] ?? null);
    }
}

final readonly class ArrayOfStringLengthMixedDto implements DTOFactory
{
    public function __construct(
        #[ArrayOfStringLength(min: 1, max: 5)]
        public mixed $items
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['items'] ?? null);
    }
}

final readonly class ArrayOfStringLengthDto implements DTOFactory
{
    /** @param list<string> $items */
    public function __construct(
        #[ArrayOfStringLength(min: 1, max: 5)]
        public array $items
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['items']);
    }
}

final readonly class ArrayOfStringLengthNullableDto implements DTOFactory
{
    /** @param list<string>|null $items */
    public function __construct(
        #[ArrayOfStringLength(min: 1, max: 5)]
        public ?array $items
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['items'] ?? null);
    }
}

final readonly class ArrayOfDto implements DTOFactory
{
    /** @param list<ChildDto> $children */
    public function __construct(
        #[ArrayOf(ChildDto::class)]
        public array $children
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        $mapped = AttributeValidator::normalize(self::class, $data, ValidationException::class);

        return new self($mapped['children']);
    }
}

final readonly class ArrayOfNullableDto implements DTOFactory
{
    /** @param list<ChildDto>|null $children */
    public function __construct(
        #[ArrayOf(ChildDto::class)]
        public ?array $children
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        $mapped = AttributeValidator::normalize(self::class, $data, ValidationException::class);

        return new self($mapped['children'] ?? null);
    }
}

final readonly class ChildDto implements DTOFactory
{
    public function __construct(public string $value) {}

    public static function from(array $data): static
    {
        return new self((string) $data['value']);
    }
}

final readonly class OptionalParamDto implements DTOFactory
{
    public function __construct(
        #[Length(min: 1, max: 3)]
        public ?string $value = null
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        return new self($data['value'] ?? null);
    }
}
