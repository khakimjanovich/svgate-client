<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Internal;

final class Redactor
{
    private const array SENSITIVE_KEYS = [
        'pan',
        'expiry',
        'code',
        'otp',
        'password',
        'token',
        'accessToken',
        'refreshToken',
    ];

    public function redactArray(array $data): array
    {
        $redacted = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $redacted[$key] = $this->redactArray($value);

                continue;
            }

            if (is_string($key) && $this->isSensitiveKey($key)) {
                $redacted[$key] = $this->mask((string) $value);

                continue;
            }

            $redacted[$key] = $value;
        }

        return $redacted;
    }

    private function isSensitiveKey(string $key): bool
    {
        $key = strtolower($key);
        foreach (self::SENSITIVE_KEYS as $needle) {
            if ($key === strtolower($needle)) {
                return true;
            }
        }

        return false;
    }

    private function mask(string $value): string
    {
        $trimmed = trim($value);
        $length = strlen($trimmed);
        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        return str_repeat('*', $length - 4).substr($trimmed, -4);
    }
}
