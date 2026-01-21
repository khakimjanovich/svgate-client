<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Terminals\Get;

use Khakimjanovich\SVGate\Exceptions\ResponseException;

final class TerminalInfo
{
    public function __construct(
        public readonly int $pid,
        public readonly string $terminalId,
        public readonly string $merchantId,
        public readonly string $username,
        public readonly int $terminalType,
        public readonly string $instId,
        public readonly string $name,
        public readonly int $port,
        public readonly string $purpose
    ) {}

    public static function fromArray(
        array $data,
        int|string|null $rpcId = null,
        ?int $httpStatus = null,
        ?string $rawResponse = null
    ): self {
        $terminalType = $data['terminalType'] ?? $data['terminal_type'] ?? $data['t_type'] ?? null;

        $required = ['pid', 'terminalId', 'merchantId', 'username', 'instId', 'name', 'port', 'purpose'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in terminal.get response item: '.$field,
                    $rpcId,
                    $httpStatus,
                    $rawResponse
                );
            }
        }

        if ($terminalType === null) {
            throw new ResponseException(
                'Missing field in terminal.get response item: terminalType',
                $rpcId,
                $httpStatus,
                $rawResponse
            );
        }

        return new self(
            (int) $data['pid'],
            (string) $data['terminalId'],
            (string) $data['merchantId'],
            (string) $data['username'],
            (int) $terminalType,
            (string) $data['instId'],
            (string) $data['name'],
            (int) $data['port'],
            (string) $data['purpose']
        );
    }
}
