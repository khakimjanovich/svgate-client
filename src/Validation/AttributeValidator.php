<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Validation;

use Khakimjanovich\SVGate\Validation\Attributes\ArrayOf;
use Khakimjanovich\SVGate\Validation\Attributes\ValidationRule;
use ReflectionAttribute;
use ReflectionException;
use ReflectionMethod;

final class AttributeValidator
{
    /**
     * @param  array<string, mixed>  $args
     *
     * @throws ReflectionException
     */
    public static function validate(string $className, array $args, string $exceptionClass): void
    {
        self::normalize($className, $args, $exceptionClass);
    }

    /**
     * @param  array<string, mixed>  $args
     * @return array<string, mixed>
     *
     * @throws ReflectionException
     */
    public static function normalize(string $className, array $args, string $exceptionClass): array
    {
        unset($args['this']);

        $constructor = new ReflectionMethod($className, '__construct');

        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();
            if (! array_key_exists($name, $args)) {
                continue;
            }

            $value = $args[$name];
            $allowsNull = $parameter->getType()?->allowsNull() ?? true;

            $attributes = $parameter->getAttributes(ValidationRule::class, ReflectionAttribute::IS_INSTANCEOF);
            foreach ($attributes as $attribute) {
                $rule = $attribute->newInstance();
                if ($rule instanceof ArrayOf) {
                    $value = $rule->map($value, $name, $allowsNull, $exceptionClass);
                    $args[$name] = $value;

                    continue;
                }

                $rule->validate($value, $name, $allowsNull, $exceptionClass);
            }
        }

        return $args;
    }
}
