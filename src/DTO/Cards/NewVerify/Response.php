<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\NewVerify;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class Response implements DTOFactory
{
    public function __construct(
        public string $id,
        public string $username,
        public string $pan,
        public int $status,
        public string $phone,
        public string $fullName,
        public int $balance,
        public bool $sms,
        public int $pincnt,
        public string $aacct,
        public string $par,
        public string $cardtype,
        public int $holdAmount,
        public int $cashbackAmount
    ) {}

    public static function from(array $data): static
    {
        $holdAmount = $data['holdAmount'] ?? $data['holdamount'] ?? null;
        $cashbackAmount = $data['cashbackAmount'] ?? $data['cashbackamount'] ?? null;

        $required = [
            'id',
            'username',
            'pan',
            'status',
            'phone',
            'fullName',
            'balance',
            'sms',
            'pincnt',
            'aacct',
            'par',
            'cardtype',
        ];

        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in cards.new.verify response: '.$field,
                    null,
                    null,
                    null,
                    null,
                    RPCErrors::SDK_RESPONSE_MISSING_FIELD
                );
            }
        }

        if ($holdAmount === null || $cashbackAmount === null) {
            throw new ResponseException(
                'Missing field in cards.new.verify response: holdAmount/cashbackAmount',
                null,
                null,
                null,
                null,
                RPCErrors::SDK_RESPONSE_MISSING_FIELD
            );
        }

        return new self(
            (string) $data['id'],
            (string) $data['username'],
            (string) $data['pan'],
            (int) $data['status'],
            (string) $data['phone'],
            (string) $data['fullName'],
            (int) $data['balance'],
            (bool) $data['sms'],
            (int) $data['pincnt'],
            (string) $data['aacct'],
            (string) $data['par'],
            (string) $data['cardtype'],
            (int) $holdAmount,
            (int) $cashbackAmount
        );
    }
}
