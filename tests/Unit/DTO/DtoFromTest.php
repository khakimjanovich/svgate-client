<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Bins\List\BinInfo;
use Khakimjanovich\SVGate\DTO\Bins\List\Payload as BinsListPayload;
use Khakimjanovich\SVGate\DTO\Bins\List\Response as BinsListResponse;
use Khakimjanovich\SVGate\DTO\Cards\Get\CardInfo;
use Khakimjanovich\SVGate\DTO\Cards\Get\Payload as CardsGetPayload;
use Khakimjanovich\SVGate\DTO\Cards\Get\Response as CardsGetResponse;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload as NewOtpPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Response as NewOtpResponse;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\SmsData;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\OtpData;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Payload as NewVerifyPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Response as NewVerifyResponse;
use Khakimjanovich\SVGate\DTO\Contracts\DTOFactory;
use Khakimjanovich\SVGate\DTO\Hold\Create\HoldData;
use Khakimjanovich\SVGate\DTO\Hold\Create\Payload as HoldCreatePayload;
use Khakimjanovich\SVGate\DTO\Hold\Create\Response as HoldCreateResponse;
use Khakimjanovich\SVGate\DTO\P2p\Info\Payload as P2pInfoPayload;
use Khakimjanovich\SVGate\DTO\P2p\Info\Response as P2pInfoResponse;
use Khakimjanovich\SVGate\DTO\P2p\Universal\P2pData;
use Khakimjanovich\SVGate\DTO\P2p\Universal\Payload as P2pUniversalPayload;
use Khakimjanovich\SVGate\DTO\P2p\Universal\Response as P2pUniversalResponse;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\CreditData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\DocData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\Payload as P2pUniversalCreditPayload;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\Response as P2pUniversalCreditResponse;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\SenderData;
use Khakimjanovich\SVGate\DTO\Terminals\Get\Payload as TerminalsGetPayload;
use Khakimjanovich\SVGate\DTO\Terminals\Get\Response as TerminalsGetResponse;
use Khakimjanovich\SVGate\DTO\Terminals\Get\TerminalInfo;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\MerchantInfo;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\Payload as PayPurposePayload;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\Response as PayPurposeResponse;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\TranData;
use Khakimjanovich\SVGate\DTO\Trans\Sv\Payload as TransSvPayload;
use Khakimjanovich\SVGate\DTO\Trans\Sv\Response as TransSvResponse;
use Khakimjanovich\SVGate\DTO\Trans\Sv\TransactionInfo;

it('dto from returns instance', function (string $className, array $payload): void {
    /** @var DTOFactory $className */
    $dto = $className::from($payload);

    expect($dto)->toBeInstanceOf($className);
})->with('dtoCases');

dataset('dtoCases', function (): array {
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

    $terminalInfo = [
        'pid' => 1,
        'terminalId' => '91419475',
        'merchantId' => '90050017182',
        'username' => 'user',
        'terminalType' => 1,
        'instId' => '01',
        'name' => 'Terminal',
        'port' => 443,
        'purpose' => 'DEFAULT',
    ];

    $transactionInfo = [
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
        'merchantId' => '90050017182',
        'terminalId' => '91419475',
        'resp' => 0,
        'respSV' => '0',
        'status' => 'OK',
    ];

    return [
        'bins.list.payload' => [BinsListPayload::class, []],
        'terminals.get.payload' => [TerminalsGetPayload::class, []],
        'cards.new.otp.card' => [CardData::class, ['pan' => '8600490000001234', 'expiry' => '2605']],
        'cards.new.otp.sms' => [SmsData::class, ['ussd' => 'ussd', 'hash' => 'hash', 'templateId' => 12]],
        'cards.new.otp.payload' => [NewOtpPayload::class, ['card' => ['pan' => '8600490000001234', 'expiry' => '2605'], 'serviceName' => 'svc']],
        'cards.new.verify.otp' => [OtpData::class, ['id' => 1, 'code' => '123456']],
        'cards.new.verify.payload' => [NewVerifyPayload::class, ['otp' => ['id' => 1, 'code' => '123456']]],
        'cards.get.payload' => [CardsGetPayload::class, ['12345678901234567890123456789012']],
        'p2p.info.payload' => [P2pInfoPayload::class, ['hpan' => '8600123412341234']],
        'p2p.universal.p2p' => [P2pData::class, [
            'sender' => 'TOKEN123',
            'recipient' => 'TOKEN456',
            'amount' => 1000,
            'ext' => 'ext-1',
            'merchantId' => '90050017182',
            'terminalId' => '91419475',
            'feeAmount' => 100,
        ]],
        'p2p.universal.payload' => [P2pUniversalPayload::class, ['p2p' => [
            'sender' => 'TOKEN123',
            'recipient' => 'TOKEN456',
            'amount' => 1000,
            'ext' => 'ext-1',
            'merchantId' => '90050017182',
            'terminalId' => '91419475',
        ]]],
        'p2p.universal.credit.doc' => [DocData::class, [
            'nationality' => '860',
            'type' => 'passport',
            'seriesNumber' => 'AA1234567',
            'birthDate' => '04041999',
            'validTo' => '04052025',
            'mrz' => '12345678912345',
        ]],
        'p2p.universal.credit.sender' => [SenderData::class, [
            'id' => '9860123456789012',
            'legalName' => 'Legal Name',
            'system' => 'humo',
            'lastName' => 'IVANOV',
            'firstName' => 'IVAN',
            'middleName' => 'IVANOVICH',
            'refNum' => '123456789012',
        ]],
        'p2p.universal.credit.credit' => [CreditData::class, [
            'amount' => 110000,
            'ext' => 'p2p_credit1',
            'merchantId' => '90050017182',
            'terminalId' => '91419475',
            'recipient' => 'F6F4834E645681549A',
            'sender' => [
                'id' => '9860123456789012',
                'legalName' => 'Legal Name',
                'system' => 'humo',
                'lastName' => 'IVANOV',
                'firstName' => 'IVAN',
                'middleName' => 'IVANOVICH',
                'refNum' => '123456789012',
            ],
        ]],
        'p2p.universal.credit.payload' => [P2pUniversalCreditPayload::class, [
            'credit' => [
                'amount' => 110000,
                'ext' => 'p2p_credit1',
                'merchantId' => '90050017182',
                'terminalId' => '91419475',
                'recipient' => 'F6F4834E645681549A',
                'sender' => [
                    'id' => '9860123456789012',
                    'legalName' => 'Legal Name',
                    'system' => 'humo',
                    'lastName' => 'IVANOV',
                    'firstName' => 'IVAN',
                    'middleName' => 'IVANOVICH',
                    'refNum' => '123456789012',
                ],
            ],
        ]],
        'trans.pay.purpose.merchant' => [MerchantInfo::class, [
            'mcc' => '6010',
            'legalName' => 'TEST Legal Name',
            'legalType' => 1,
            'legalId' => '98745621',
            'legalOKED' => '01500',
        ]],
        'trans.pay.purpose.tran' => [TranData::class, [
            'purpose' => 'payment',
            'receiverId' => '2305197202',
            'amount' => 100000,
            'cardId' => '72B811D79F904298916E9054AF8D7DD4',
            'commission' => 0,
            'currency' => '860',
            'ext' => '95762e53-722c-4e0d-a3bb-e3ca5d2599f1',
            'merchantId' => '906722023',
            'terminalId' => '9000339',
            'merchantInfo' => [
                'mcc' => '6010',
                'legalName' => 'TEST Legal Name',
                'legalType' => 1,
                'legalId' => '98745621',
                'legalOKED' => '01500',
            ],
        ]],
        'trans.pay.purpose.payload' => [PayPurposePayload::class, [
            'tran' => [
                'purpose' => 'payment',
                'receiverId' => '2305197202',
                'amount' => 100000,
                'cardId' => '72B811D79F904298916E9054AF8D7DD4',
                'commission' => 0,
                'currency' => '860',
                'ext' => '95762e53-722c-4e0d-a3bb-e3ca5d2599f1',
                'merchantId' => '906722023',
                'terminalId' => '9000339',
                'merchantInfo' => [
                    'mcc' => '6010',
                    'legalName' => 'TEST Legal Name',
                    'legalType' => 1,
                    'legalId' => '98745621',
                    'legalOKED' => '01500',
                ],
            ],
        ]],
        'trans.sv.payload' => [TransSvPayload::class, ['svId' => '123456789012']],
        'hold.create.hold' => [HoldData::class, [
            'cardId' => '8156315C1234567890ABCDEF12345678',
            'merchantId' => '90050017182',
            'terminalId' => '91419475',
            'amount' => 110000000,
            'time' => 60,
        ]],
        'hold.create.payload' => [HoldCreatePayload::class, [
            'hold' => [
                'cardId' => '8156315C1234567890ABCDEF12345678',
                'merchantId' => '90050017182',
                'terminalId' => '91419475',
                'amount' => 110000000,
                'time' => 60,
            ],
        ]],
        'bins.list.bininfo' => [BinInfo::class, ['instId' => '01', 'bin' => '8600']],
        'cards.get.cardinfo' => [CardInfo::class, $cardInfo],
        'terminal.get.terminalinfo' => [TerminalInfo::class, $terminalInfo],
        'trans.sv.transactioninfo' => [TransactionInfo::class, $transactionInfo],
        'bins.list.response' => [BinsListResponse::class, [
            ['instId' => '01', 'bin' => '8600'],
        ]],
        'cards.get.response' => [CardsGetResponse::class, [$cardInfo]],
        'cards.new.otp.response' => [NewOtpResponse::class, [
            'id' => 1,
            'phoneMask' => '********1234',
            'token' => '',
            'verified' => false,
        ]],
        'cards.new.verify.response' => [NewVerifyResponse::class, [
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
        ]],
        'hold.create.response' => [HoldCreateResponse::class, ['id' => 1, 'status' => 0, 'description' => 'OK']],
        'p2p.info.response' => [P2pInfoResponse::class, [
            'CREF_NO' => 'ref',
            'EMBOS_NAME' => 'Embos',
            'CARDTYPE' => 'HUMO',
            'CARDSTATUS' => 'ACTIVE',
            'CARDID' => '1',
            'PINCNT' => 0,
        ]],
        'p2p.universal.response' => [P2pUniversalResponse::class, [
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
        ]],
        'p2p.universal.credit.response' => [P2pUniversalCreditResponse::class, [
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
        ]],
        'terminal.get.response' => [TerminalsGetResponse::class, [$terminalInfo]],
        'trans.pay.purpose.response' => [PayPurposeResponse::class, [
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
        ]],
        'trans.sv.response' => [TransSvResponse::class, [$transactionInfo]],
    ];
});
