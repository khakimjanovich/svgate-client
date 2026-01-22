<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Configs\ClientOptions;
use Khakimjanovich\SVGate\SVGate;
use Khakimjanovich\SVGate\Tests\Unit\Support\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Log\NullLogger;

it('svgate helper returns client', function (): void {
    $httpClient = new FakeHttpClient(new Response(200, [], ''));
    $factory = new Psr17Factory;
    $config = new ClientOptions(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $factory,
        $factory,
        new NullLogger
    );

    $client = svgate($config);

    expect($client)->toBeInstanceOf(SVGate::class);
});
