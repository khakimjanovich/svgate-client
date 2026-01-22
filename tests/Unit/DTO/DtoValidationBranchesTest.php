<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\OtpData;
use Khakimjanovich\SVGate\DTO\Hold\Create\HoldData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\DocData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\SenderData;
use Khakimjanovich\SVGate\Exceptions\ValidationException;

it('card data requires pan and expiry', function (): void {
    expect(fn () => CardData::from(['pan' => '8600123412345678']))
        ->toThrow(ValidationException::class);
});

it('card data maps to array', function (): void {
    $card = new CardData('8600123412345678', '2605');

    expect($card->toArray())->toBe([
        'pan' => '8600123412345678',
        'expiry' => '2605',
    ]);
});

it('otp data requires id and code', function (): void {
    expect(fn () => OtpData::from(['id' => 1]))
        ->toThrow(ValidationException::class);
});

it('otp data maps to array', function (): void {
    $otp = new OtpData(1, '123456');

    expect($otp->toArray())->toBe([
        'id' => 1,
        'code' => '123456',
    ]);
});

it('hold data requires all fields', function (): void {
    expect(fn () => HoldData::from(['cardId' => 'card']))
        ->toThrow(ValidationException::class);
});

it('hold data maps to array', function (): void {
    $hold = new HoldData('card', 'merchant', 'terminal', 100, 60);

    expect($hold->toArray())->toBe([
        'cardId' => 'card',
        'merchantId' => 'merchant',
        'terminalId' => 'terminal',
        'amount' => 100,
        'time' => 60,
    ]);
});

it('sender data requires required fields', function (): void {
    expect(fn () => SenderData::from(['id' => '1']))
        ->toThrow(ValidationException::class);
});

it('sender data maps doc when present', function (): void {
    $doc = new DocData('860', 'passport', 'AA1234567', '04041999', '04052025', '12345678912345');
    $sender = new SenderData(
        '9860123456789012',
        'Legal Name',
        'humo',
        'IVANOV',
        'IVAN',
        'IVANOVICH',
        '123456789012',
        $doc
    );

    expect($sender->toArray())->toBe([
        'id' => '9860123456789012',
        'legalName' => 'Legal Name',
        'system' => 'humo',
        'lastName' => 'IVANOV',
        'firstName' => 'IVAN',
        'middleName' => 'IVANOVICH',
        'refNum' => '123456789012',
        'doc' => $doc->toArray(),
    ]);
});

it('sender data omits doc when null', function (): void {
    $sender = new SenderData(
        '9860123456789012',
        'Legal Name',
        'humo',
        'IVANOV',
        'IVAN',
        'IVANOVICH',
        '123456789012'
    );

    expect($sender->toArray())->toBe([
        'id' => '9860123456789012',
        'legalName' => 'Legal Name',
        'system' => 'humo',
        'lastName' => 'IVANOV',
        'firstName' => 'IVAN',
        'middleName' => 'IVANOVICH',
        'refNum' => '123456789012',
    ]);
});
