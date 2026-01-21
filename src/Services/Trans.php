<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\Payload as PayPurposePayload;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\Response as PayPurposeResponse;
use Khakimjanovich\SVGate\DTO\Trans\Sv\Payload as SvPayload;
use Khakimjanovich\SVGate\DTO\Trans\Sv\Response as SvResponse;
use Khakimjanovich\SVGate\Internal\JsonRpcCaller;
use Random\RandomException;

final readonly class Trans
{
    public function __construct(private JsonRpcCaller $caller) {}

    /**
     * @throws RandomException
     */
    public function payPurpose(PayPurposePayload $request): PayPurposeResponse
    {
        $result = $this->caller->call('trans.pay.purpose', $request->toParams());

        return PayPurposeResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }

    /**
     * @throws RandomException
     */
    public function sv(SvPayload $request): SvResponse
    {
        $result = $this->caller->call('trans.sv', $request->toParams());

        return SvResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }
}
