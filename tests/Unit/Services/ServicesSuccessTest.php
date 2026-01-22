<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Configs\ClientOptions;
use Khakimjanovich\SVGate\DTO\Bins\List\Payload as BinsListPayload;
use Khakimjanovich\SVGate\DTO\Cards\Get\Payload as CardsGetPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload as NewOtpPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\OtpData;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Payload as NewVerifyPayload;
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
use Khakimjanovich\SVGate\SVGate;
use Khakimjanovich\SVGate\Tests\Unit\Support\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Log\NullLogger;

it('service call success', function (string $method, callable $call, array $expectedParams, array $result): void {
    $httpClient = new FakeHttpClient(function (RequestInterface $request) use ($result): Response {
        $payload = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $responseBody = json_encode([
            'jsonrpc' => '2.0',
            'id' => $payload['id'],
            'result' => $result,
        ], JSON_THROW_ON_ERROR);

        return new Response(200, ['Content-Type' => 'application/json'], $responseBody);
    });

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
    $response = $call($client);

    expect($response)->not()->toBeNull();

    $request = $httpClient->requests[0];
    $payload = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
    expect($payload['method'])->toBe($method);
    expect($payload['params'])->toBe($expectedParams);
})->with('serviceCalls');

dataset('serviceCalls', function (): array {
    $card = new CardData('8600490000001234', '2605');
    $otp = new OtpData(1, '123456');

    $doc = new DocData('860', 'passport', 'AA1234567', '04041999', '04052025', '12345678912345');
    $sender = new SenderData('9860123456789012', 'Legal Name', 'humo', 'IVANOV', 'IVAN', 'IVANOVICH', '123456789012', $doc);
    $credit = new CreditData(110000, 'p2p_credit1', '90050017182', '91419475', 'F6F4834E645681549A', $sender);

    $p2p = new P2pData('TOKEN123', 'TOKEN456', 2500000, 'ext-001', '90050017182', '91419475', 25000);

    $merchantInfo = new MerchantInfo('6010', 'TEST Legal Name', 1, '98745621', '01500');
    $tran = new TranData('payment', '2305197202', 100000, '72B811D79F904298916E9054AF8D7DD4', 0, '860', '95762e53-722c-4e0d-a3bb-e3ca5d2599f1', '906722023', '9000339', $merchantInfo);

    $hold = new HoldData('8156315C1234567890ABCDEF12345678', '90050017182', '91419475', 110000000, 60);

    $cardInfo = [
        'id' => '1',
        'username' => 'user',
        'pan' => '8600123412345678',
        'expiry' => '2605',
        'status' => 1,
        'phone' => '998901234567',
        'fullName' => 'Test User',
        'balance' => 100,
        'sms' => true,
        'pincnt' => 0,
        'aacct' => 'aacct',
        'par' => 'par',
        'cardtype' => 'HUMO',
        'holdAmount' => 0,
        'cashbackAmount' => 0,
    ];

    return [
        'cards.new.otp' => [
            'cards.new.otp',
            static fn (SVGate $client) => $client->cards()->newOtp(new NewOtpPayload($card, 'my-service')),
            [
                'card' => ['pan' => '8600490000001234', 'expiry' => '2605'],
                'serviceName' => 'my-service',
            ],
            ['id' => 1, 'phoneMask' => '********1234', 'token' => '', 'verified' => false],
        ],
        'cards.new.verify' => [
            'cards.new.verify',
            static fn (SVGate $client) => $client->cards()->newVerify(new NewVerifyPayload($otp)),
            ['otp' => ['id' => 1, 'code' => '123456']],
            [
                'id' => '1',
                'username' => 'user',
                'pan' => '8600123412345678',
                'status' => 1,
                'phone' => '998901234567',
                'fullName' => 'Test User',
                'balance' => 100,
                'sms' => true,
                'pincnt' => 0,
                'aacct' => 'aacct',
                'par' => 'par',
                'cardtype' => 'HUMO',
                'holdAmount' => 0,
                'cashbackAmount' => 0,
            ],
        ],
        'cards.get' => [
            'cards.get',
            static fn (SVGate $client) => $client->cards()->get(new CardsGetPayload(['12345678901234567890123456789012'])),
            ['ids' => ['12345678901234567890123456789012']],
            [$cardInfo],
        ],
        'get.bin.list' => [
            'get.bin.list',
            static fn (SVGate $client) => $client->bins()->list(new BinsListPayload),
            [],
            [['instId' => '01', 'bin' => '8600']],
        ],
        'terminal.get' => [
            'terminal.get',
            static fn (SVGate $client) => $client->terminals()->get(new TerminalsGetPayload),
            [],
            [[
                'pid' => 1,
                'terminalId' => '91419475',
                'merchantId' => '90050017182',
                'username' => 'user',
                'terminalType' => 1,
                'instId' => '01',
                'name' => 'Terminal',
                'port' => 443,
                'purpose' => 'DEFAULT',
            ]],
        ],
        'p2p.info' => [
            'p2p.info',
            static fn (SVGate $client) => $client->p2p()->info(new P2pInfoPayload('8600123412341234')),
            ['hpan' => '8600123412341234'],
            [
                'CREF_NO' => 'ref',
                'EMBOS_NAME' => 'Embos',
                'CARDTYPE' => 'HUMO',
                'CARDSTATUS' => 'ACTIVE',
                'CARDID' => '1',
                'PINCNT' => 0,
            ],
        ],
        'p2p.universal' => [
            'p2p.universal',
            static fn (SVGate $client) => $client->p2p()->universal(new P2pUniversalPayload($p2p)),
            ['p2p' => $p2p->toArray()],
            [
                'id' => '1',
                'username' => 'user',
                'refNum' => 'ref-1',
                'ext' => 'ext-1',
                'pan' => '8600123412345678',
                'pan2' => '9860123412345678',
                'expiry' => '2605',
                'tranType' => 'P2P',
                'transType' => 1,
                'date7' => '0701',
                'date12' => '0701123456',
                'amount' => 1000,
                'currency' => '860',
                'stan' => '123456',
                'field38' => '654321',
                'field48' => null,
                'field91' => null,
                'merchantId' => '90050017182',
                'terminalId' => '91419475',
                'resp' => 0,
                'respText' => null,
                'respSV' => '0',
                'status' => 'OK',
                'refNumDebit' => 'ref-debit',
                'refNumCredit' => 'ref-credit',
            ],
        ],
        'p2p.universal.credit' => [
            'p2p.universal.credit',
            static fn (SVGate $client) => $client->p2p()->universalCredit(new P2pUniversalCreditPayload($credit)),
            ['credit' => $credit->toArray()],
            [
                'id' => '1',
                'username' => 'user',
                'refNum' => 'ref-1',
                'ext' => 'ext-1',
                'pan' => '8600123412345678',
                'pan2' => '9860123412345678',
                'expiry' => '2605',
                'tranType' => 'P2P',
                'transType' => 1,
                'date7' => '0701',
                'date12' => '0701123456',
                'amount' => 1000,
                'currency' => '860',
                'stan' => '123456',
                'field38' => '654321',
                'field48' => null,
                'field91' => null,
                'merchantId' => '90050017182',
                'terminalId' => '91419475',
                'resp' => 0,
                'respText' => null,
                'respSV' => '0',
                'status' => 'OK',
            ],
        ],
        'trans.pay.purpose' => [
            'trans.pay.purpose',
            static fn (SVGate $client) => $client->trans()->payPurpose(new PayPurposePayload($tran)),
            ['tran' => $tran->toArray()],
            [
                'id' => '1',
                'username' => 'user',
                'refNum' => 'ref-1',
                'ext' => 'ext-1',
                'pan' => '8600123412345678',
                'tranType' => 'PAY',
                'date7' => '0701',
                'date12' => '0701123456',
                'amount' => 1000,
                'currency' => '860',
                'stan' => '123456',
                'merchantId' => '90050017182',
                'terminalId' => '91419475',
                'resp' => 0,
                'respText' => null,
                'respSV' => '0',
                'status' => 'OK',
            ],
        ],
        'trans.sv' => [
            'trans.sv',
            static fn (SVGate $client) => $client->trans()->sv(new TransSvPayload('123456789012')),
            ['svId' => '123456789012'],
            [[
                'id' => '1',
                'username' => 'user',
                'refNum' => 'ref-1',
                'ext' => 'ext-1',
                'pan' => '8600123412345678',
                'pan2' => '9860123412345678',
                'expiry' => '2605',
                'tranType' => 'P2P',
                'date7' => '0701',
                'date12' => '0701123456',
                'amount' => 1000,
                'currency' => '860',
                'stan' => '123456',
                'field38' => '654321',
                'field48' => null,
                'field91' => null,
                'merchantId' => '90050017182',
                'terminalId' => '91419475',
                'resp' => 0,
                'respText' => null,
                'respSV' => '0',
                'status' => 'OK',
            ]],
        ],
        'hold.create' => [
            'hold.create',
            static fn (SVGate $client) => $client->hold()->create(new HoldCreatePayload($hold)),
            ['hold' => $hold->toArray()],
            ['id' => 1, 'status' => 0, 'description' => 'OK'],
        ],
    ];
});
