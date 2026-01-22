<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Universal;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class Response implements DTOFactory
{
    public function __construct(
        public string $id,
        public string $username,
        public string $refNum,
        public string $ext,
        public string $pan,
        public string $pan2,
        public string $expiry,
        public string $tranType,
        public int $transType,
        public string $date7,
        public string $date12,
        public int $amount,
        public string $currency,
        public string $stan,
        public string $field38,
        public ?string $field48,
        public ?string $field91,
        public string $merchantId,
        public string $terminalId,
        public int $resp,
        public ?string $respText,
        public string $respSV,
        public string $status,
        public string $refNumDebit,
        public string $refNumCredit
    ) {}

    public static function from(array $data): static
    {
        $required = [
            'id',
            'username',
            'refNum',
            'ext',
            'pan',
            'pan2',
            'expiry',
            'tranType',
            'transType',
            'date7',
            'date12',
            'amount',
            'currency',
            'stan',
            'field38',
            'merchantId',
            'terminalId',
            'resp',
            'respSV',
            'status',
            'refNumDebit',
            'refNumCredit',
        ];

        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in p2p.universal response: '.$field,
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
            (string) $data['pan2'],
            (string) $data['expiry'],
            (string) $data['tranType'],
            (int) $data['transType'],
            (string) $data['date7'],
            (string) $data['date12'],
            (int) $data['amount'],
            (string) $data['currency'],
            (string) $data['stan'],
            (string) $data['field38'],
            array_key_exists('field48', $data) && $data['field48'] !== null ? (string) $data['field48'] : null,
            array_key_exists('field91', $data) && $data['field91'] !== null ? (string) $data['field91'] : null,
            (string) $data['merchantId'],
            (string) $data['terminalId'],
            (int) $data['resp'],
            array_key_exists('respText', $data) && $data['respText'] !== null ? (string) $data['respText'] : null,
            (string) $data['respSV'],
            (string) $data['status'],
            (string) $data['refNumDebit'],
            (string) $data['refNumCredit']
        );
    }
}
