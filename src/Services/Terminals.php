<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\Terminals\Get\Payload as GetPayload;
use Khakimjanovich\SVGate\DTO\Terminals\Get\Response as GetResponse;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Internal\JsonRpcCaller;
use Random\RandomException;

final readonly class Terminals
{
    public function __construct(private JsonRpcCaller $caller) {}

    /**
     * @throws RandomException
     */
    public function get(GetPayload $request): GetResponse
    {
        $result = $this->caller->call($request->method(), $request->toParams());

        try {
            return GetResponse::from($result->result);
        } catch (ResponseException $exception) {
            throw new ResponseException(
                $exception->getMessage(),
                $result->rpcId,
                $result->httpStatus,
                $result->rawResponse,
                $exception,
                (int) $exception->getCode()
            );
        }
    }
}
