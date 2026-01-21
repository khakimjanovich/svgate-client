<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate\Tests;

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
use PHPUnit\Framework\TestCase;

final class ValidationNewEndpointsTest extends TestCase
{
    public function test_cards_get_requires_ids(): void
    {
        $this->expectException(ValidationException::class);

        new CardsGetPayload([]);
    }

    public function test_p2p_info_requires_hpan(): void
    {
        $this->expectException(ValidationException::class);

        new P2pInfoPayload('123');
    }

    public function test_p2p_universal_requires_amount(): void
    {
        $this->expectException(ValidationException::class);

        new P2pData('TOKEN', 'TOKEN2', 0, 'ext', 'merchant', 'terminal');
    }

    public function test_p2p_universal_credit_requires_sender(): void
    {
        $this->expectException(ValidationException::class);

        new CreditData(100, 'ext', 'merchant', 'terminal', 'recipient', new SenderData('', 'Legal', 'sys', 'L', 'F', 'M', 'ref'));
    }

    public function test_trans_pay_purpose_requires_receiver(): void
    {
        $this->expectException(ValidationException::class);

        new TranData('payment', '', 100, 'card', 0, '860', 'ext', 'merchant', 'terminal', new MerchantInfo('6010', 'Name', 1, '123', '01500'));
    }

    public function test_trans_sv_requires_sv_id(): void
    {
        $this->expectException(ValidationException::class);

        new TransSvPayload('');
    }

    public function test_hold_create_requires_amount(): void
    {
        $this->expectException(ValidationException::class);

        new HoldData('card', 'merchant', 'terminal', 0, 10);
    }
}
