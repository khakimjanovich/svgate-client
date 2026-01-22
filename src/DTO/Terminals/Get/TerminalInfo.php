<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\DTO\Terminals\Get;

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

final readonly class TerminalInfo implements DTOFactory
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

    public static function from(array $data): static
    {
        $terminalType = $data['terminalType'] ?? $data['terminal_type'] ?? $data['t_type'] ?? null;

        $required = ['pid', 'terminalId', 'merchantId', 'username', 'instId', 'name', 'port', 'purpose'];
        foreach ($required as $field) {
            if (! array_key_exists($field, $data)) {
                throw new ResponseException(
                    'Missing field in terminal.get response item: '.$field,
                    null,
                    null,
                    null,
                    null,
                    RPCErrors::SDK_RESPONSE_MISSING_FIELD
                );
            }
        }

        if ($terminalType === null) {
            throw new ResponseException(
                'Missing field in terminal.get response item: terminalType',
                null,
                null,
                null,
                null,
                RPCErrors::SDK_RESPONSE_MISSING_FIELD
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

    /**
     * @return list<TerminalInfo>
     */
    public static function collect(array $items): array
    {
        $terminals = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                throw new ResponseException(
                    'Invalid terminal.get response item shape.',
                    null,
                    null,
                    null,
                    null,
                    RPCErrors::SDK_RESPONSE_INVALID_ITEM
                );
            }

            $terminals[] = self::from($item);
        }

        return $terminals;
    }
}
