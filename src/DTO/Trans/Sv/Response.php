<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\Sv;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayOf;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Response implements DTOFactory
{
    /** @var list<TransactionInfo> */
    public array $transactions;

    /** @param list<TransactionInfo> $transactions */
    public function __construct(
        #[ArrayOf(TransactionInfo::class)]
        array $transactions
    ) {
        $this->transactions = $transactions;
    }

    public static function from(array $data): static
    {
        $mapped = AttributeValidator::normalize(self::class, ['transactions' => $data], ResponseException::class);

        return new self($mapped['transactions']);
    }
}
