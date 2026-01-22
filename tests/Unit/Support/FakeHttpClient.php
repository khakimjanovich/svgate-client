<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Tests\Unit\Support;

use Closure;
use InvalidArgumentException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FakeHttpClient implements ClientInterface
{
    /** @var list<RequestInterface> */
    public array $requests = [];

    private ResponseInterface|Closure $responseFactory;

    /** @param ResponseInterface|callable $responseFactory */
    public function __construct(mixed $responseFactory)
    {
        if ($responseFactory instanceof ResponseInterface) {
            $this->responseFactory = $responseFactory;

            return;
        }

        if (! is_callable($responseFactory)) {
            throw new InvalidArgumentException('Response factory must be a ResponseInterface or callable.');
        }

        $this->responseFactory = $responseFactory(...);
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->requests[] = $request;

        if ($this->responseFactory instanceof Closure) {
            return ($this->responseFactory)($request);
        }

        return $this->responseFactory;
    }
}
