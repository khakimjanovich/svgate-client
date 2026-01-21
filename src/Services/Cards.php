<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\Cards\Get\Payload as GetPayload;
use Khakimjanovich\SVGate\DTO\Cards\Get\Response as GetResponse;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload as NewOtpPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Response as NewOtpResponse;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Payload as NewVerifyPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Response as NewVerifyResponse;
use Khakimjanovich\SVGate\Internal\JsonRpcCaller;
use Random\RandomException;

final readonly class Cards
{
    public function __construct(private JsonRpcCaller $caller) {}

    /**
     * @throws RandomException
     */
    public function newOtp(NewOtpPayload $request): NewOtpResponse
    {
        $result = $this->caller->call('cards.new.otp', $request->toParams());

        return NewOtpResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }

    /**
     * @throws RandomException
     */
    public function newVerify(NewVerifyPayload $request): NewVerifyResponse
    {
        $result = $this->caller->call('cards.new.verify', $request->toParams());

        return NewVerifyResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }

    /**
     * @throws RandomException
     */
    public function get(GetPayload $request): GetResponse
    {
        $result = $this->caller->call('cards.get', $request->toParams());

        return GetResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }
}
