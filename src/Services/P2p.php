<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\P2p\Info\Payload as InfoPayload;
use Khakimjanovich\SVGate\DTO\P2p\Info\Response as InfoResponse;
use Khakimjanovich\SVGate\DTO\P2p\Universal\Payload as UniversalPayload;
use Khakimjanovich\SVGate\DTO\P2p\Universal\Response as UniversalResponse;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\Payload as UniversalCreditPayload;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\Response as UniversalCreditResponse;
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
        $result = $this->caller->call('p2p.info', $request->toParams());

        return InfoResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }

    /**
     * @throws RandomException
     */
    public function universal(UniversalPayload $request): UniversalResponse
    {
        $result = $this->caller->call('p2p.universal', $request->toParams());

        return UniversalResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }

    /**
     * @throws RandomException
     */
    public function universalCredit(UniversalCreditPayload $request): UniversalCreditResponse
    {
        $result = $this->caller->call('p2p.universal.credit', $request->toParams());

        return UniversalCreditResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }
}
