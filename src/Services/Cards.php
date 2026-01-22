<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\Cards\Get\Payload as GetPayload;
use Khakimjanovich\SVGate\DTO\Cards\Get\Response as GetResponse;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload as NewOtpPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Response as NewOtpResponse;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Payload as NewVerifyPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Response as NewVerifyResponse;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
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
        $result = $this->caller->call($request->method(), $request->toParams());

        try {
            return NewOtpResponse::from($result->result);
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
    public function newVerify(NewVerifyPayload $request): NewVerifyResponse
    {
        return NewVerifyResponse::from(
            $this->caller
                ->call($request->method(), $request->toParams())
                ->result
        );
    }

    /**
     * @throws RandomException
     */
    public function get(GetPayload $request): GetResponse
    {
        return GetResponse::from(
            $this->caller
                ->call($request->method(), $request->toParams())
                ->result
        );
    }
}
