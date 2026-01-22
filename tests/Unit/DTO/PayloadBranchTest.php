<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Cards\NewOTP\CardData;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\Payload as NewOtpPayload;
use Khakimjanovich\SVGate\DTO\Cards\NewOTP\SmsData;

it('cards.new.otp payload omits empty sms and requestor phone', function (): void {
    $payload = new NewOtpPayload(
        new CardData('8600490000001234', '2605'),
        'service',
        new SmsData
    );

    expect($payload->toParams())->toBe([
        'card' => ['pan' => '8600490000001234', 'expiry' => '2605'],
        'serviceName' => 'service',
    ]);
});
