<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\UniversalCredit;

use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\Attributes\PositiveInt;
use Khakimjanovich\SVGate\Validation\AttributeValidator;
use ReflectionException;

final readonly class CreditData implements DTOFactory
{
    /**
     * @throws ReflectionException
     */
    public function __construct(
        #[PositiveInt]
        public int $amount,
        #[Length(min: 1, max: 75)]
        public string $ext,
        #[Length(min: 1, max: 16)]
        public string $merchantId,
        #[Length(min: 1, max: 16)]
        public string $terminalId,
        #[Length(min: 1, max: 32)]
        public string $recipient,
        public SenderData $sender
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        $required = ['amount', 'ext', 'merchantId', 'terminalId', 'recipient', 'sender'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ValidationException('creditData requires '.$field.'.');
            }
        }

        $sender = is_array($data['sender']) ? SenderData::from($data['sender']) : $data['sender'];

        return new self(
            (int) $data['amount'],
            (string) $data['ext'],
            (string) $data['merchantId'],
            (string) $data['terminalId'],
            (string) $data['recipient'],
            $sender
        );
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'ext' => $this->ext,
            'merchantId' => $this->merchantId,
            'terminalId' => $this->terminalId,
            'recipient' => $this->recipient,
            'sender' => $this->sender->toArray(),
        ];
    }
}
