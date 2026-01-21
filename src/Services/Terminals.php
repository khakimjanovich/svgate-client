<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\Terminals\Get\Payload as GetPayload;
use Khakimjanovich\SVGate\DTO\Terminals\Get\Response as GetResponse;
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
        $result = $this->caller->call('terminal.get', $request->toParams());

        return GetResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }
}
