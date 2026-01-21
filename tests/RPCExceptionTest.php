<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Tests;

use Khakimjanovich\SVGate\Configs\ClientOptions;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload;
use Khakimjanovich\SVGate\Exceptions\RPCException;
use Khakimjanovich\SVGate\SVGate;
use Khakimjanovich\SVGate\Tests\Support\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

final class RPCExceptionTest extends TestCase
{
    public function test_json_rpc_error_throws_api_exception(): void
    {
        $responseBody = json_encode([
            'jsonrpc' => '2.0',
            'id' => 123,
            'error' => [
                'code' => -320,
                'message' => 'Phone mismatch.',
            ],
        ], JSON_THROW_ON_ERROR);

        $response = new Response(200, ['Content-Type' => 'application/json'], $responseBody);
        $httpClient = new FakeHttpClient($response);
        $psr17Factory = new Psr17Factory;

        $config = new ClientOptions(
            'https://example.test/api',
            'user',
            'pass',
            $httpClient,
            $psr17Factory,
            $psr17Factory,
            new NullLogger
        );

        $client = new SVGate($config);

        $this->expectException(RPCException::class);

        $request = new Payload(
            new CardData('8600490000001234', '2605'),
            'my-service'
        );

        $client->cards()->newOtp($request);
    }
}
