<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\P2p\Universal;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final class Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $username,
        public readonly string $refNum,
        public readonly string $ext,
        public readonly string $pan,
        public readonly string $pan2,
        public readonly string $expiry,
        public readonly string $tranType,
        public readonly int $transType,
        public readonly string $date7,
        public readonly string $date12,
        public readonly int $amount,
        public readonly string $currency,
        public readonly string $stan,
        public readonly string $field38,
        public readonly ?string $field48,
        public readonly ?string $field91,
        public readonly string $merchantId,
        public readonly string $terminalId,
        public readonly int $resp,
        public readonly ?string $respText,
        public readonly string $respSV,
        public readonly string $status,
        public readonly string $refNumDebit,
        public readonly string $refNumCredit
    ) {}

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
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
                    $rpcId,
                    $httpStatus,
                    $rawResponse
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
