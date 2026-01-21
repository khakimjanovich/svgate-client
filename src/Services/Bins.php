<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Services;

use Khakimjanovich\SVGate\DTO\Bins\List\Payload as ListPayload;
use Khakimjanovich\SVGate\DTO\Bins\List\Response as ListResponse;
use Khakimjanovich\SVGate\Internal\JsonRpcCaller;
use Random\RandomException;

final readonly class Bins
{
    public function __construct(private JsonRpcCaller $caller) {}

    /**
     * @throws RandomException
     */
    public function list(ListPayload $request): ListResponse
    {
        $result = $this->caller->call('get.bin.list', $request->toParams());

        return ListResponse::fromArray($result->result, $result->rpcId, $result->httpStatus, $result->rawResponse);
    }
}
