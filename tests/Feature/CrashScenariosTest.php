<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Configs\ClientOptions;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload as NewOtpPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\OtpData;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Payload as NewVerifyPayload;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Exceptions\RPCException;
use Khakimjanovich\SVGate\Exceptions\TransportException;
use Khakimjanovich\SVGate\SVGate;
use Khakimjanovich\SVGate\Tests\Unit\Support\FakeHttpClient;
use Khakimjanovich\SVGate\Tests\Unit\Support\TestLogger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

it('throws TransportException when the http client fails', function (): void {
    [$client, $httpClient, $logger] = clientWithResponse(function (): ResponseInterface {
        throw new FeatureClientException('connection refused');
    });

    $payload = new NewOtpPayload(new CardData('8600490000001234', '2605'), 'svc');

    expect(fn () => $client->cards()->newOtp($payload))
        ->toThrow(TransportException::class);

    expect($httpClient->requests)->toHaveCount(1);
    $messages = array_column($logger->records, 'message');
    expect($messages)->toContain('SVGate transport error.');
});

it('throws RPCException when json-rpc error payload is returned', function (): void {
    $body = json_encode([
        'jsonrpc' => '2.0',
        'id' => 42,
        'error' => ['code' => -32000, 'message' => 'Unauthorized'],
    ], JSON_THROW_ON_ERROR);

    [$client] = clientWithResponse(new Response(200, ['Content-Type' => 'application/json'], $body));

    $payload = new NewOtpPayload(new CardData('8600490000001234', '2605'), 'svc');

    expect(fn () => $client->cards()->newOtp($payload))
        ->toThrow(RPCException::class);
});

it('throws ResponseException when required response fields are missing', function (): void {
    $body = json_encode([
        'jsonrpc' => '2.0',
        'id' => 9,
        'result' => ['id' => '1'],
    ], JSON_THROW_ON_ERROR);

    [$client] = clientWithResponse(new Response(200, ['Content-Type' => 'application/json'], $body));

    $payload = new NewVerifyPayload(new OtpData(1, '123456'));

    expect(fn () => $client->cards()->newVerify($payload))
        ->toThrow(ResponseException::class);
});

/**
 * @return array{0: SVGate, 1: FakeHttpClient, 2: TestLogger}
 */
function clientWithResponse(ResponseInterface|callable $responseFactory): array
{
    $httpClient = new FakeHttpClient($responseFactory);
    $factory = new Psr17Factory;
    $logger = new TestLogger;

    $config = new ClientOptions(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        $logger
    );

    return [new SVGate($config), $httpClient, $logger];
}

final class FeatureClientException extends RuntimeException implements ClientExceptionInterface {}
