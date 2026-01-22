<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload as NewOtpPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\SmsData;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Payload as NewVerifyPayload;
use Khakimjanovich\SVGate\DTO\Hold\Create\Payload as HoldCreatePayload;
use Khakimjanovich\SVGate\DTO\P2p\Info\Payload as P2pInfoPayload;
use Khakimjanovich\SVGate\DTO\P2p\Universal\P2pData;
use Khakimjanovich\SVGate\DTO\P2p\Universal\Payload as P2pUniversalPayload;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\CreditData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\DocData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\Payload as P2pUniversalCreditPayload;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\SenderData;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\MerchantInfo;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\Payload as PayPurposePayload;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\TranData;
use Khakimjanovich\SVGate\DTO\Trans\Sv\Payload as TransSvPayload;
use Khakimjanovich\SVGate\Exceptions\ValidationException;

it('cards.new.otp from requires card and service name', function (): void {
    expect(fn () => NewOtpPayload::from(['serviceName' => 'svc']))
        ->toThrow(ValidationException::class);
});

it('cards.new.otp from maps arrays and optional fields', function (): void {
    $payload = NewOtpPayload::from([
        'card' => ['pan' => '8600490000001234', 'expiry' => '2605'],
        'serviceName' => 'svc',
        'sms' => ['ussd' => 'ussd', 'hash' => 'hash', 'templateId' => 1],
        'requestorPhone' => '998901234567',
    ]);

    expect($payload->card)->toBeInstanceOf(CardData::class);
    expect($payload->sms)->toBeInstanceOf(SmsData::class);
    expect($payload->requestorPhone)->toBe('998901234567');
});

it('cards.new.otp from keeps provided dto instances', function (): void {
    $card = new CardData('8600490000001234', '2605');
    $sms = new SmsData('ussd', 'hash', 1);

    $payload = NewOtpPayload::from([
        'card' => $card,
        'serviceName' => 'svc',
        'sms' => $sms,
    ]);

    expect($payload->card)->toBe($card);
    expect($payload->sms)->toBe($sms);
});

it('cards.new.verify from requires otp', function (): void {
    expect(fn () => NewVerifyPayload::from([]))
        ->toThrow(ValidationException::class);
});

it('p2p.info from requires hpan', function (): void {
    expect(fn () => P2pInfoPayload::from([]))
        ->toThrow(ValidationException::class);
});

it('p2p.universal from requires p2p', function (): void {
    expect(fn () => P2pUniversalPayload::from([]))
        ->toThrow(ValidationException::class);
});

it('p2p.universal from keeps provided dto instances', function (): void {
    $p2p = new P2pData('TOKEN123', 'TOKEN456', 2500000, 'ext-001', '90050017182', '91419475', 25000);

    $payload = P2pUniversalPayload::from(['p2p' => $p2p]);

    expect($payload->p2p)->toBe($p2p);
});

it('p2p.universal.credit from requires credit', function (): void {
    expect(fn () => P2pUniversalCreditPayload::from([]))
        ->toThrow(ValidationException::class);
});

it('p2p.universal.credit from keeps provided dto instances', function (): void {
    $doc = new DocData('860', 'passport', 'AA1234567', '04041999', '04052025', '12345678912345');
    $sender = new SenderData('9860123456789012', 'Legal Name', 'humo', 'IVANOV', 'IVAN', 'IVANOVICH', '123456789012', $doc);
    $credit = new CreditData(110000, 'p2p_credit1', '90050017182', '91419475', 'F6F4834E645681549A', $sender);

    $payload = P2pUniversalCreditPayload::from(['credit' => $credit]);

    expect($payload->credit)->toBe($credit);
});

it('trans.pay.purpose from requires tran', function (): void {
    expect(fn () => PayPurposePayload::from([]))
        ->toThrow(ValidationException::class);
});

it('trans.pay.purpose from keeps provided dto instances', function (): void {
    $merchant = new MerchantInfo('6010', 'TEST Legal Name', 1, '98745621', '01500');
    $tran = new TranData('payment', '2305197202', 100000, '72B811D79F904298916E9054AF8D7DD4', 0, '860', '95762e53-722c-4e0d-a3bb-e3ca5d2599f1', '906722023', '9000339', $merchant);

    $payload = PayPurposePayload::from(['tran' => $tran]);

    expect($payload->tran)->toBe($tran);
});

it('trans.sv from requires svId', function (): void {
    expect(fn () => TransSvPayload::from([]))
        ->toThrow(ValidationException::class);
});

it('hold.create from requires hold', function (): void {
    expect(fn () => HoldCreatePayload::from([]))
        ->toThrow(ValidationException::class);
});
