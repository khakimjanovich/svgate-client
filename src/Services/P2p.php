<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\P2p\Info\Payload as InfoPayload;
use Khakimjanovich\SVGate\DTO\P2p\Info\Response as InfoResponse;
use Khakimjanovich\SVGate\DTO\P2p\Universal\Payload as UniversalPayload;
use Khakimjanovich\SVGate\DTO\P2p\Universal\Response as UniversalResponse;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\Payload as UniversalCreditPayload;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\Response as UniversalCreditResponse;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Internal\JsonRpcCaller;
use Random\RandomException;

final readonly class P2p
{
    public function __construct(private JsonRpcCaller $caller) {}

    /**
     * @throws RandomException
     */
    public function info(InfoPayload $request): InfoResponse
    {
        $result = $this->caller->call($request->method(), $request->toParams());

        try {
            return InfoResponse::from($result->result);
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

    /**
     * @throws RandomException
     */
    public function universal(UniversalPayload $request): UniversalResponse
    {
        $result = $this->caller->call($request->method(), $request->toParams());

        try {
            return UniversalResponse::from($result->result);
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

    /**
     * @throws RandomException
     */
    public function universalCredit(UniversalCreditPayload $request): UniversalCreditResponse
    {
        $result = $this->caller->call($request->method(), $request->toParams());

        try {
            return UniversalCreditResponse::from($result->result);
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
