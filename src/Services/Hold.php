<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\Hold\Create\Payload as CreatePayload;
use Khakimjanovich\SVGate\DTO\Hold\Create\Response as CreateResponse;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Internal\JsonRpcCaller;
use Random\RandomException;

final readonly class Hold
{
    public function __construct(private JsonRpcCaller $caller) {}

    /**
     * @throws RandomException
     */
    public function create(CreatePayload $request): CreateResponse
    {
        $result = $this->caller->call($request->method(), $request->toParams());

        try {
            return CreateResponse::from($result->result);
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
