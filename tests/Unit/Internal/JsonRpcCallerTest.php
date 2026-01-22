<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Exceptions\RPCException;
use Khakimjanovich\SVGate\Exceptions\TransportException;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Khakimjanovich\SVGate\Internal\JsonRpcCaller;
use Khakimjanovich\SVGate\Internal\Redactor;
use Khakimjanovich\SVGate\Tests\Unit\Support\FakeHttpClient;
use Khakimjanovich\SVGate\Tests\Unit\Support\TestLogger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

it('call builds request and returns result', function (): void {
    $result = ['ok' => true];
    $responseBody = json_encode([
        'jsonrpc' => '2.0',
        'id' => 99,
        'result' => $result,
    ], JSON_THROW_ON_ERROR);

    $httpClient = new FakeHttpClient(new Response(200, ['Content-Type' => 'application/json'], $responseBody));
    $factory = new Psr17Factory;
    $logger = new TestLogger;
    $caller = new JsonRpcCaller(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        $logger,
        new Redactor
    );

    $rpcResult = $caller->call('cards.new.otp', ['card' => ['pan' => '8600123412345678', 'expiry' => '2605']]);

    expect($rpcResult->result)->toBe($result);
    expect($rpcResult->rpcId)->toBe(99);
    expect($rpcResult->httpStatus)->toBe(200);

    $request = $httpClient->requests[0];
    expect($request->getHeaderLine('Content-Type'))->toBe('application/json; charset=utf-8');
    expect($request->getHeaderLine('Accept'))->toBe('application/json');
    expect($request->getHeaderLine('Authorization'))->toBe('Basic '.base64_encode('user:pass'));

    $payload = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
    expect($payload['jsonrpc'])->toBe('2.0');
    expect($payload['method'])->toBe('cards.new.otp');
    expect($payload['params'])->toBe(['card' => ['pan' => '8600123412345678', 'expiry' => '2605']]);
    expect($payload['id'])->toBeInt();

    expect($logger->records)->not()->toBeEmpty();
    expect($logger->records[0]['context']['params']['card']['pan'])->toBe('************5678');
    expect($logger->records[0]['context']['params']['card']['expiry'])->toBe('****');
});

it('invalid json response throws response exception', function (): void {
    $httpClient = new FakeHttpClient(new Response(200, ['Content-Type' => 'application/json'], 'nope'));
    $factory = new Psr17Factory;
    $caller = new JsonRpcCaller(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        new TestLogger,
        new Redactor
    );

    try {
        $caller->call('cards.new.otp', ['card' => ['pan' => '8600', 'expiry' => '2605']]);
        throw new RuntimeException('Expected ResponseException not thrown.');
    } catch (ResponseException $exception) {
        expect($exception->getCode())->toBe(RPCErrors::SDK_RESPONSE_INVALID_JSON);
    }
});

it('json encode failure throws validation exception', function (): void {
    $httpClient = new FakeHttpClient(new Response(200, ['Content-Type' => 'application/json'], ''));
    $factory = new Psr17Factory;
    $caller = new JsonRpcCaller(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        new TestLogger,
        new Redactor
    );

    $invalidUtf8 = "\xB1\x31";
    expect(fn () => $caller->call('cards.new.otp', ['bad' => $invalidUtf8]))
        ->toThrow(ValidationException::class);
});

it('unexpected envelope throws response exception', function (): void {
    $responseBody = json_encode([
        'jsonrpc' => '1.0',
        'id' => 77,
        'result' => ['ok' => true],
    ], JSON_THROW_ON_ERROR);

    $httpClient = new FakeHttpClient(new Response(200, ['Content-Type' => 'application/json'], $responseBody));
    $factory = new Psr17Factory;
    $caller = new JsonRpcCaller(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        new TestLogger,
        new Redactor
    );

    try {
        $caller->call('cards.new.otp', ['card' => ['pan' => '8600', 'expiry' => '2605']]);
        throw new RuntimeException('Expected ResponseException not thrown.');
    } catch (ResponseException $exception) {
        expect($exception->getCode())->toBe(RPCErrors::SDK_RESPONSE_INVALID_ENVELOPE);
    }
});

it('missing result throws response exception', function (): void {
    $responseBody = json_encode([
        'jsonrpc' => '2.0',
        'id' => 77,
    ], JSON_THROW_ON_ERROR);

    $httpClient = new FakeHttpClient(new Response(200, ['Content-Type' => 'application/json'], $responseBody));
    $factory = new Psr17Factory;
    $caller = new JsonRpcCaller(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        new TestLogger,
        new Redactor
    );

    try {
        $caller->call('cards.new.otp', ['card' => ['pan' => '8600', 'expiry' => '2605']]);
        throw new RuntimeException('Expected ResponseException not thrown.');
    } catch (ResponseException $exception) {
        expect($exception->getCode())->toBe(RPCErrors::SDK_RESPONSE_MISSING_RESULT);
    }
});

it('non-array result throws response exception', function (): void {
    $responseBody = json_encode([
        'jsonrpc' => '2.0',
        'id' => 88,
        'result' => 'ok',
    ], JSON_THROW_ON_ERROR);

    $httpClient = new FakeHttpClient(new Response(200, ['Content-Type' => 'application/json'], $responseBody));
    $factory = new Psr17Factory;
    $caller = new JsonRpcCaller(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        new TestLogger,
        new Redactor
    );

    try {
        $caller->call('cards.new.otp', ['card' => ['pan' => '8600', 'expiry' => '2605']]);
        throw new RuntimeException('Expected ResponseException not thrown.');
    } catch (ResponseException $exception) {
        expect($exception->getCode())->toBe(RPCErrors::SDK_RESPONSE_RESULT_SHAPE);
    }
});

it('malformed error object throws response exception', function (): void {
    $responseBody = json_encode([
        'jsonrpc' => '2.0',
        'id' => 11,
        'error' => ['message' => 'fail'],
    ], JSON_THROW_ON_ERROR);

    $httpClient = new FakeHttpClient(new Response(200, ['Content-Type' => 'application/json'], $responseBody));
    $factory = new Psr17Factory;
    $caller = new JsonRpcCaller(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        new TestLogger,
        new Redactor
    );

    try {
        $caller->call('cards.new.otp', ['card' => ['pan' => '8600', 'expiry' => '2605']]);
        throw new RuntimeException('Expected ResponseException not thrown.');
    } catch (ResponseException $exception) {
        expect($exception->getCode())->toBe(RPCErrors::SDK_RESPONSE_MALFORMED_ERROR);
    }
});

it('error response throws rpc exception with details', function (): void {
    $responseBody = json_encode([
        'jsonrpc' => '2.0',
        'id' => 12,
        'error' => [
            'code' => -320,
            'message' => 'Failed',
        ],
    ], JSON_THROW_ON_ERROR);

    $httpClient = new FakeHttpClient(new Response(200, ['Content-Type' => 'application/json'], $responseBody));
    $factory = new Psr17Factory;
    $caller = new JsonRpcCaller(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        new TestLogger,
        new Redactor
    );

    try {
        $caller->call('cards.new.otp', ['card' => ['pan' => '8600', 'expiry' => '2605']]);
        throw new RuntimeException('Expected RPCException not thrown.');
    } catch (RPCException $exception) {
        expect($exception->errorCode)->toBe(-320);
        expect($exception->errorMessage)->toBe('Failed');
        expect($exception->rpcId)->toBe(12);
    }
});

it('transport exception is wrapped', function (): void {
    $factory = new Psr17Factory;
    $caller = new JsonRpcCaller(
        'https://example.test/api',
        'user',
        'pass',
        new ThrowingClient,
        $factory,
        $factory,
        new TestLogger,
        new Redactor
    );

    expect(fn () => $caller->call('cards.new.otp', ['card' => ['pan' => '8600', 'expiry' => '2605']]))
        ->toThrow(TransportException::class);
});

final class ThrowingClient implements ClientInterface
{
    public function sendRequest(RequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        throw new DummyClientException('boom');
    }
}

final class DummyClientException extends RuntimeException implements ClientExceptionInterface {}
