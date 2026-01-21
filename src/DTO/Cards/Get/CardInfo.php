<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Cards\Get;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final class CardInfo
{
    public function __construct(
        public readonly string $id,
        public readonly string $username,
        public readonly string $pan,
        public readonly string $expiry,
        public readonly int $status,
        public readonly string $phone,
        public readonly string $fullName,
        public readonly int $balance,
        public readonly bool $sms,
        public readonly int $pincnt,
        public readonly string $aacct,
        public readonly string $par,
        public readonly string $cardtype,
        public readonly int $holdAmount,
        public readonly int $cashbackAmount
    ) {}

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        $holdAmount = $data['holdAmount'] ?? $data['holdamount'] ?? null;
        $cashbackAmount = $data['cashbackAmount'] ?? $data['cashbackamount'] ?? null;

        $required = [
            'id',
            'username',
            'pan',
            'expiry',
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
                    'Missing field in cards.get response item: '.$field,
                    $rpcId,
                    $httpStatus,
                    $rawResponse
                );
            }
        }

        if ($holdAmount === null || $cashbackAmount === null) {
            throw new ResponseException(
                'Missing field in cards.get response item: holdAmount/cashbackAmount',
                $rpcId,
                $httpStatus,
                $rawResponse
            );
        }

        return new self(
            (string) $data['id'],
            (string) $data['username'],
            (string) $data['pan'],
            (string) $data['expiry'],
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
