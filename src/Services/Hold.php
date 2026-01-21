<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\Hold\Create\Payload as CreatePayload;
use Khakimjanovich\SVGate\DTO\Hold\Create\Response as CreateResponse;
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
        $result = $this->caller->call('hold.create', $request->toParams());

        return CreateResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }
}
