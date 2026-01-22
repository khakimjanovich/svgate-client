<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewOTP;

use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Validation\Attributes\Digits;
use Khakimjanovich\SVGate\Validation\Attributes\Length;
use Khakimjanovich\SVGate\Validation\AttributeValidator;

final readonly class Payload implements PayloadContract
{
    public function __construct(
        public CardData $card,
        #[Length(min: 1, max: 20)]
        public string $serviceName,
        public ?SmsData $sms = null,
        #[Digits(minLength: 1, maxLength: 12)]
        public ?string $requestorPhone = null
    ) {
        AttributeValidator::validate(self::class, get_defined_vars(), ValidationException::class);
    }

    public static function from(array $data): static
    {
        if (! array_key_exists('card', $data) || ! array_key_exists('serviceName', $data)) {
            throw new ValidationException('cards.new.otp payload requires card and serviceName.');
        }

        $card = is_array($data['card']) ? CardData::from($data['card']) : $data['card'];
        $sms = null;
        if (array_key_exists('sms', $data)) {
            $sms = is_array($data['sms']) ? SmsData::from($data['sms']) : $data['sms'];
        }

        return new self(
            $card,
            (string) $data['serviceName'],
            $sms,
            $data['requestorPhone'] ?? null
        );
    }

    public function method(): string
    {
        return 'cards.new.otp';
    }

    public function toParams(): array
    {
        $params = [
            'card' => $this->card->toArray(),
            'serviceName' => $this->serviceName,
        ];

        if ($this->sms !== null) {
            $smsPayload = $this->sms->toArray();
            if ($smsPayload !== []) {
                $params['sms'] = $smsPayload;
            }
        }

        if ($this->requestorPhone !== null) {
            $params['requestorPhone'] = $this->requestorPhone;
        }

        return $params;
    }
}
