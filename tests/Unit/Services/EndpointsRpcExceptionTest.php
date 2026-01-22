<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Configs\ClientOptions;
use Khakimjanovich\SVGate\DTO\Bins\List\Payload as BinsListPayload;
use Khakimjanovich\SVGate\DTO\Cards\Get\Payload as CardsGetPayload;
use Khakimjanovich\SVGate\DTO\Hold\Create\HoldData;
use Khakimjanovich\SVGate\DTO\Hold\Create\Payload as HoldCreatePayload;
use Khakimjanovich\SVGate\DTO\P2p\Info\Payload as P2pInfoPayload;
use Khakimjanovich\SVGate\DTO\P2p\Universal\P2pData;
use Khakimjanovich\SVGate\DTO\P2p\Universal\Payload as P2pUniversalPayload;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\CreditData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\DocData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\Payload as P2pUniversalCreditPayload;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\SenderData;
use Khakimjanovich\SVGate\DTO\Terminals\Get\Payload as TerminalsGetPayload;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\MerchantInfo;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\Payload as PayPurposePayload;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\TranData;
use Khakimjanovich\SVGate\DTO\Trans\Sv\Payload as TransSvPayload;
use Khakimjanovich\SVGate\Exceptions\RPCException;
use Khakimjanovich\SVGate\SVGate;
use Khakimjanovich\SVGate\Tests\Unit\Support\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Log\NullLogger;

it('json rpc error mapped', function (string $label, callable $call): void {
    $responseBody = json_encode([
        'jsonrpc' => '2.0',
        'id' => 123,
        'error' => [
            'code' => -200,
            'message' => 'Card not found! ',
        ],
    ], JSON_THROW_ON_ERROR);

    $response = new Response(200, ['Content-Type' => 'application/json'], $responseBody);
    $httpClient = new FakeHttpClient($response);
    $psr17Factory = new Psr17Factory;

    $config = new ClientOptions(
        'https://example.test/api',
        'user',
        'pass',
        $httpClient,
        $psr17Factory,
        $psr17Factory,
        new NullLogger
    );

    $client = new SVGate($config);

    expect(fn () => $call($client))
        ->toThrow(RPCException::class);
})->with('rpcErrorCalls');

dataset('rpcErrorCalls', function (): array {
    $doc = new DocData('860', 'passport', 'AA1234567', '04041999', '04052025', '12345678912345');
    $sender = new SenderData('9860123456789012', 'Legal Name', 'humo', 'IVANOV', 'IVAN', 'IVANOVICH', '123456789012', $doc);
    $credit = new CreditData(110000, 'p2p_credit1', '90050017182', '91419475', 'F6F4834E645681549A', $sender);

    return [
        'get.bin.list' => ['get.bin.list', static fn (SVGate $client) => $client->bins()->list(new BinsListPayload)],
        'terminal.get' => ['terminal.get', static fn (SVGate $client) => $client->terminals()->get(new TerminalsGetPayload)],
        'cards.get' => ['cards.get', static fn (SVGate $client) => $client->cards()->get(new CardsGetPayload(['12345678901234567890123456789012']))],
        'p2p.info' => ['p2p.info', static fn (SVGate $client) => $client->p2p()->info(new P2pInfoPayload('8600123412341234'))],
        'p2p.universal' => ['p2p.universal', static fn (SVGate $client) => $client->p2p()->universal(new P2pUniversalPayload(new P2pData('TOKEN123', 'TOKEN456', 2500000, 'ext-001', '90050017182', '91419475', 25000)))],
        'p2p.universal.credit' => ['p2p.universal.credit', static fn (SVGate $client) => $client->p2p()->universalCredit(new P2pUniversalCreditPayload($credit))],
        'trans.pay.purpose' => ['trans.pay.purpose', static fn (SVGate $client) => $client->trans()->payPurpose(new PayPurposePayload(new TranData('payment', '2305197202', 100000, '72B811D79F904298916E9054AF8D7DD4', 0, '860', '95762e53-722c-4e0d-a3bb-e3ca5d2599f1', '906722023', '9000339', new MerchantInfo('6010', 'TEST Legal Name', 1, '98745621', '01500'))))],
        'trans.sv' => ['trans.sv', static fn (SVGate $client) => $client->trans()->sv(new TransSvPayload('123456789012'))],
        'hold.create' => ['hold.create', static fn (SVGate $client) => $client->hold()->create(new HoldCreatePayload(new HoldData('8156315C1234567890ABCDEF12345678', '90050017182', '91419475', 110000000, 60)))],
    ];
});
