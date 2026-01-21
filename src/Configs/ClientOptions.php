<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Configs;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

final readonly class ClientOptions
{
    public function __construct(
        public string $endpoint,
        public string $username,
        public string $password,
        public ClientInterface $httpClient,
        public RequestFactoryInterface $requestFactory,
        public StreamFactoryInterface $streamFactory,
        public LoggerInterface $logger
    ) {}
}
