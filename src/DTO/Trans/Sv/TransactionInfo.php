<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Trans\Sv;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class TransactionInfo implements DTOFactory
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
            'pan2',
            'expiry',
            'tranType',
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
        ];

        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in trans.sv response item: '.$field,
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
            (string) $data['status']
        );
    }

    /**
     * @return list<TransactionInfo>
     */
    public static function collect(array $items): array
    {
        $transactions = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                throw new ResponseException(
                    'Invalid trans.sv response item shape.',
                    null,
                    null,
                    null,
                    null,
                    RPCErrors::SDK_RESPONSE_INVALID_ITEM
                );
            }

            $transactions[] = self::from($item);
        }

        return $transactions;
    }
}
