# SVGate PHP SDK

Tiny, framework-agnostic PHP 8.4 SDK for the SVGate JSON-RPC API.

## Installation

```bash
composer require khakimjanovich/svgate
```

You must provide PSR-18 client, PSR-17 factories, and a PSR-3 logger.

## Quick start

```php
<?php

declare(strict_types=1);

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Log\NullLogger;
use Khakimjanovich\SVGate\SVGate;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload as NewOtpPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\OtpData;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Payload as NewVerifyPayload;
use Khakimjanovich\SVGate\Configs\ClientOptions;

$psr17 = new Psr17Factory();
$httpClient = new \GuzzleHttp\Client(['http_errors' => false]);

$config = new ClientOptions(
    'https://svgate.example/api',
    'api-user',
    'api-pass',
    $httpClient,
    $psr17,
    $psr17,
    new NullLogger()
);

$client = svgate($config);

$otpResponse = $client->cards()->newOtp(new NewOtpPayload(
    new CardData('8600490000001234', '2605'),
    'my-service'
));

$verifyResponse = $client->cards()->newVerify(new NewVerifyPayload(
    new OtpData($otpResponse->id, '123456')
));

$bins = $client->bins()->list(new Khakimjanovich\SVGate\DTO\Bins\List\Payload());
$terminals = $client->terminals()->get(new Khakimjanovich\SVGate\DTO\Terminals\Get\Payload());
$cardDetails = $client->cards()->get(new Khakimjanovich\SVGate\DTO\Cards\Get\Payload(['CARD_ID']));
$p2pInfo = $client->p2p()->info(new Khakimjanovich\SVGate\DTO\P2p\Info\Payload('8600123412341234'));
```

## Card add flow (OTP + verify)

```php
$otp = $client->cards()->newOtp(new NewOtpPayload(
    new CardData('8600490000001234', '2605'),
    'my-service'
));

$verified = $client->cards()->newVerify(new NewVerifyPayload(
    new OtpData($otp->id, '123456')
));

$token = $verified->id; // tokenized card id
```

## Errors

All errors are thrown as typed exceptions:

- `Khakimjanovich\SVGate\Exceptions\RPCException` for JSON-RPC errors.
- `Khakimjanovich\SVGate\Exceptions\TransportException` for HTTP/PSR-18 errors.
- `Khakimjanovich\SVGate\Exceptions\ResponseException` for invalid JSON or unexpected responses.
- `Khakimjanovich\SVGate\Exceptions\ValidationException` for invalid request DTOs.

`RPCException` and `ResponseException` keep useful debugging data:
- JSON-RPC id
- error code/message
- HTTP status (when relevant)
- raw response payload (safe to log)

## Logging and redaction

A PSR-3 logger is required and is used for request creation, response receipt, and error mapping. Sensitive fields are redacted (PAN, expiry, OTP code, passwords, tokens). The logger receives already-redacted payloads.

## Folder structure

- `src/Services` — public service modules grouped by domain (Cards, Bins, Terminals, P2p, Trans, Hold).
- `src` — main SDK client entry point (`SVGate.php`).
- `src/DTO` — immutable endpoint DTOs grouped by method; Payload and Response types per endpoint.
- `src/DTO/Contracts` — shared DTO construction contracts (`DTOFactory`, `PayloadContract`).
- `src/Exceptions` — typed SDK exceptions.
- `src/Internal` — internal JSON-RPC caller and redaction helpers.
- `src/Validation` — attribute-based DTO validation rules and validator.
- `tests` — unit tests and fake transport.

## Testing

```bash
composer install
composer test
composer test-coverage
```

Tests are written in Pest and run via the `composer test` script.
Coverage requires an installed driver (Xdebug or PCOV). `composer test-coverage` enforces 95% per-class coverage.

## Formatting

```bash
composer format
composer format:check
```

CI guidance: run `composer format:check` alongside tests to enforce coding style.
