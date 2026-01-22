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
        return ListResponse::from(
            $this->caller
                ->call($request->method(), $request->toParams())
                ->result
        );
    }
}
