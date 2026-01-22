<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Internal;

use JsonException;
use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Exceptions\RPCException;
use Khakimjanovich\SVGate\Exceptions\TransportException;
use Khakimjanovich\SVGate\Exceptions\ValidationException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Random\RandomException;

/**
 * @internal
 */
final readonly class JsonRpcCaller
{
    public function __construct(
        private string $endpoint,
        private string $username,
        private string $password,
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private LoggerInterface $logger,
        private Redactor $redactor
    ) {}

    /**
     * @throws RandomException
     */
    public function call(string $method, array $params): JsonRpcResult
    {
        $rpcId = random_int(1, PHP_INT_MAX);
        $payload = [
            'jsonrpc' => '2.0',
            'method' => $method,
            'id' => $rpcId,
            'params' => $params,
        ];

        try {
            $body = json_encode($payload, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ValidationException('Failed to encode JSON-RPC payload.', $exception);
        }

        $this->logger->info('SVGate request created.', [
            'method' => $method,
            'rpc_id' => $rpcId,
            'endpoint' => $this->endpoint,
            'params' => $this->redactor->redactArray($params),
        ]);

        $request = $this->requestFactory->createRequest('POST', $this->endpoint)
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Authorization', 'Basic '.base64_encode($this->username.':'.$this->password))
            ->withBody($this->streamFactory->createStream($body));

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $exception) {
            $this->logger->error('SVGate transport error.', [
                'method' => $method,
                'rpc_id' => $rpcId,
            ]);
            throw new TransportException('Transport error while calling SVGate.', null, null, $exception);
        }

        $statusCode = $response->getStatusCode();
        $rawResponse = (string) $response->getBody();

        $this->logger->info('SVGate response received.', [
            'rpc_id' => $rpcId,
            'status' => $statusCode,
        ]);

        try {
            $decoded = json_decode($rawResponse, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ResponseException(
                'Invalid JSON-RPC response.',
                $rpcId,
                $statusCode,
                $rawResponse,
                $exception,
                RPCErrors::SDK_RESPONSE_INVALID_JSON
            );
        }

        if (! is_array($decoded) || ($decoded['jsonrpc'] ?? null) !== '2.0') {
            throw new ResponseException(
                'Unexpected JSON-RPC envelope.',
                $rpcId,
                $statusCode,
                $rawResponse,
                null,
                RPCErrors::SDK_RESPONSE_INVALID_ENVELOPE
            );
        }

        $responseId = $decoded['id'] ?? null;
        if (array_key_exists('error', $decoded)) {
            $error = $decoded['error'];
            if (! is_array($error) || ! isset($error['code'], $error['message'])) {
                throw new ResponseException(
                    'Malformed JSON-RPC error object.',
                    $responseId,
                    $statusCode,
                    $rawResponse,
                    null,
                    RPCErrors::SDK_RESPONSE_MALFORMED_ERROR
                );
            }

            $errorCode = (int) $error['code'];
            $errorMessage = (string) $error['message'];

            $this->logger->error('SVGate JSON-RPC error mapped.', [
                'rpc_id' => $responseId,
                'code' => $errorCode,
                'message' => $errorMessage,
            ]);

            throw new RPCException(
                'SVGate API error: '.$errorMessage,
                $responseId,
                $errorCode,
                $errorMessage,
                $statusCode,
                $rawResponse
            );
        }

        if (! array_key_exists('result', $decoded)) {
            throw new ResponseException(
                'JSON-RPC response missing result.',
                $responseId,
                $statusCode,
                $rawResponse,
                null,
                RPCErrors::SDK_RESPONSE_MISSING_RESULT
            );
        }

        $result = $decoded['result'];
        if (! is_array($result)) {
            throw new ResponseException(
                'JSON-RPC result has unexpected shape.',
                $responseId,
                $statusCode,
                $rawResponse,
                null,
                RPCErrors::SDK_RESPONSE_RESULT_SHAPE
            );
        }

        return new JsonRpcResult($result, $responseId, $statusCode, $rawResponse);
    }
}
