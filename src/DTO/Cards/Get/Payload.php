<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\Get;

use Khakimjanovich\SVGate\Exceptions\ValidationException;

final class Payload
{
    /** @var list<string> */
    public readonly array $ids;

    /** @param list<string> $ids */
    public function __construct(array $ids)
    {
        if ($ids === []) {
            throw new ValidationException('At least one card id is required.');
        }

        foreach ($ids as $id) {
            if (! is_string($id) || $id === '' || strlen($id) > 32) {
                throw new ValidationException('Card id must be a non-empty string up to 32 characters.');
            }
        }

        $this->ids = array_values($ids);
    }

    public function toParams(): array
    {
        return ['ids' => $this->ids];
    }
}
