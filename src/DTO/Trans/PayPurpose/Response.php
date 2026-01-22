<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\PayPurpose;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class Response implements DTOFactory
{
    public function __construct(
        public readonly string $id,
        public readonly string $username,
        public readonly string $refNum,
        public readonly string $ext,
        public readonly string $pan,
        public readonly string $tranType,
        public readonly string $date7,
        public readonly string $date12,
        public readonly int $amount,
        public readonly string $currency,
        public readonly string $stan,
        public readonly string $merchantId,
        public readonly string $terminalId,
        public readonly int $resp,
        public readonly ?string $respText,
        public readonly string $respSV,
        public readonly string $status
    ) {}

    public static function from(array $data): static
    {
        $required = [
            'id',
            'username',
            'refNum',
            'ext',
            'pan',
            'tranType',
            'date7',
            'date12',
            'amount',
            'currency',
            'stan',
            'merchantId',
            'terminalId',
            'resp',
            'respSV',
            'status',
        ];

        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in trans.pay.purpose response: '.$field,
                    null,
                    null,
                    null,
                    null,
                    RPCErrors::SDK_RESPONSE_MISSING_FIELD
                );
            }
        }

        return new self(
            (string) $data['id'],
            (string) $data['username'],
            (string) $data['refNum'],
            (string) $data['ext'],
            (string) $data['pan'],
            (string) $data['tranType'],
            (string) $data['date7'],
            (string) $data['date12'],
            (int) $data['amount'],
            (string) $data['currency'],
            (string) $data['stan'],
            (string) $data['merchantId'],
            (string) $data['terminalId'],
            (int) $data['resp'],
            array_key_exists('respText', $data) && $data['respText'] !== null ? (string) $data['respText'] : null,
            (string) $data['respSV'],
            (string) $data['status']
        );
    }
}
