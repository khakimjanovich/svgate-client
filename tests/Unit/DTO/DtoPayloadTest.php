<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Bins\List\Payload as BinsListPayload;
use Khakimjanovich\SVGate\DTO\Cards\Get\Payload as CardsGetPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload as NewOtpPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\SmsData;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\OtpData;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Payload as NewVerifyPayload;
use Khakimjanovich\SVGate\DTO\Contracts\PayloadContract;
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

it('payload method and params', function (PayloadContract $payload, string $expectedMethod, array $expectedParams): void {
    expect($payload->method())->toBe($expectedMethod);
    expect($payload->toParams())->toBe($expectedParams);
})->with('payloadCases');

dataset('payloadCases', function (): array {
    $card = new CardData('8600490000001234', '2605');
    $sms = new SmsData('ussd', 'hash', 12);
    $otp = new OtpData(1, '123456');

    $doc = new DocData('860', 'passport', 'AA1234567', '04041999', '04052025', '12345678912345');
    $sender = new SenderData('9860123456789012', 'Legal Name', 'humo', 'IVANOV', 'IVAN', 'IVANOVICH', '123456789012', $doc);
    $credit = new CreditData(110000, 'p2p_credit1', '90050017182', '91419475', 'F6F4834E645681549A', $sender);

    $p2p = new P2pData('TOKEN123', 'TOKEN456', 2500000, 'ext-001', '90050017182', '91419475', 25000);

    $merchantInfo = new MerchantInfo('6010', 'TEST Legal Name', 1, '98745621', '01500');
    $tran = new TranData('payment', '2305197202', 100000, '72B811D79F904298916E9054AF8D7DD4', 0, '860', '95762e53-722c-4e0d-a3bb-e3ca5d2599f1', '906722023', '9000339', $merchantInfo);

    $hold = new HoldData('8156315C1234567890ABCDEF12345678', '90050017182', '91419475', 110000000, 60);

    return [
        'cards.new.otp' => [
            new NewOtpPayload($card, 'my-service', $sms, '998901234567'),
            'cards.new.otp',
            [
                'card' => ['pan' => '8600490000001234', 'expiry' => '2605'],
                'serviceName' => 'my-service',
                'sms' => ['ussd' => 'ussd', 'hash' => 'hash', 'templateId' => 12],
                'requestorPhone' => '998901234567',
            ],
        ],
        'cards.new.verify' => [
            new NewVerifyPayload($otp),
            'cards.new.verify',
            ['otp' => ['id' => 1, 'code' => '123456']],
        ],
        'cards.get' => [
            new CardsGetPayload(['12345678901234567890123456789012']),
            'cards.get',
            ['ids' => ['12345678901234567890123456789012']],
        ],
        'get.bin.list' => [
            new BinsListPayload,
            'get.bin.list',
            [],
        ],
        'terminal.get' => [
            new TerminalsGetPayload,
            'terminal.get',
            [],
        ],
        'p2p.info' => [
            new P2pInfoPayload('8600123412341234'),
            'p2p.info',
            ['hpan' => '8600123412341234'],
        ],
        'p2p.universal' => [
            new P2pUniversalPayload($p2p),
            'p2p.universal',
            ['p2p' => $p2p->toArray()],
        ],
        'p2p.universal.credit' => [
            new P2pUniversalCreditPayload($credit),
            'p2p.universal.credit',
            ['credit' => $credit->toArray()],
        ],
        'trans.pay.purpose' => [
            new PayPurposePayload($tran),
            'trans.pay.purpose',
            ['tran' => $tran->toArray()],
        ],
        'trans.sv' => [
            new TransSvPayload('123456789012'),
            'trans.sv',
            ['svId' => '123456789012'],
        ],
        'hold.create' => [
            new HoldCreatePayload($hold),
            'hold.create',
            ['hold' => $hold->toArray()],
        ],
    ];
});
