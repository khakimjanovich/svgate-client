<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Cards\Get\Payload as CardsGetPayload;
use Khakimjanovich\SVGate\DTO\Hold\Create\HoldData;
use Khakimjanovich\SVGate\DTO\P2p\Info\Payload as P2pInfoPayload;
use Khakimjanovich\SVGate\DTO\P2p\Universal\P2pData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\CreditData;
use Khakimjanovich\SVGate\DTO\P2p\UniversalCredit\SenderData;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\MerchantInfo;
use Khakimjanovich\SVGate\DTO\Trans\PayPurpose\TranData;
use Khakimjanovich\SVGate\DTO\Trans\Sv\Payload as TransSvPayload;
use Khakimjanovich\SVGate\Exceptions\ValidationException;

it('cards get requires ids', function (): void {
    expect(fn () => new CardsGetPayload([]))
        ->toThrow(ValidationException::class);
});

it('p2p info requires hpan', function (): void {
    expect(fn () => new P2pInfoPayload('123'))
        ->toThrow(ValidationException::class);
});

it('p2p universal requires amount', function (): void {
    expect(fn () => new P2pData('TOKEN', 'TOKEN2', 0, 'ext', 'merchant', 'terminal'))
        ->toThrow(ValidationException::class);
});

it('p2p universal credit requires sender', function (): void {
    expect(fn () => new CreditData(100, 'ext', 'merchant', 'terminal', 'recipient', new SenderData('', 'Legal', 'sys', 'L', 'F', 'M', 'ref')))
        ->toThrow(ValidationException::class);
});

it('trans pay purpose requires receiver', function (): void {
    expect(fn () => new TranData('payment', '', 100, 'card', 0, '860', 'ext', 'merchant', 'terminal', new MerchantInfo('6010', 'Name', 1, '123', '01500')))
        ->toThrow(ValidationException::class);
});

it('trans sv requires sv id', function (): void {
    expect(fn () => new TransSvPayload(''))
        ->toThrow(ValidationException::class);
});

it('hold create requires amount', function (): void {
    expect(fn () => new HoldData('card', 'merchant', 'terminal', 0, 10))
        ->toThrow(ValidationException::class);
});
