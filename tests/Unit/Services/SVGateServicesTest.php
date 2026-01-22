<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Configs\ClientOptions;
use Khakimjanovich\SVGate\Services\Bins;
use Khakimjanovich\SVGate\Services\Cards;
use Khakimjanovich\SVGate\Services\Hold;
use Khakimjanovich\SVGate\Services\P2p;
use Khakimjanovich\SVGate\Services\Terminals;
use Khakimjanovich\SVGate\Services\Trans;
use Khakimjanovich\SVGate\SVGate;
use Khakimjanovich\SVGate\Tests\Unit\Support\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Log\NullLogger;

it('services are singletons', function (): void {
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

    $client = new SVGate($config);

    expect($client->cards())->toBeInstanceOf(Cards::class);
    expect($client->cards())->toBe($client->cards());

    expect($client->bins())->toBeInstanceOf(Bins::class);
    expect($client->bins())->toBe($client->bins());

    expect($client->terminals())->toBeInstanceOf(Terminals::class);
    expect($client->terminals())->toBe($client->terminals());

    expect($client->p2p())->toBeInstanceOf(P2p::class);
    expect($client->p2p())->toBe($client->p2p());

    expect($client->trans())->toBeInstanceOf(Trans::class);
    expect($client->trans())->toBe($client->trans());

    expect($client->hold())->toBeInstanceOf(Hold::class);
    expect($client->hold())->toBe($client->hold());
});
