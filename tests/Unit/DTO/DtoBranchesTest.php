<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\DTO\Bins\List\BinInfo;
use Khakimjanovich\SVGate\DTO\Cards\Get\CardInfo;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Response as NewVerifyResponse;
use Khakimjanovich\SVGate\DTO\Terminals\Get\TerminalInfo;
use Khakimjanovich\SVGate\DTO\Trans\Sv\TransactionInfo;
use Khakimjanovich\SVGate\Exceptions\ResponseException;

it('bin info collects valid items', function (): void {
    $items = [
        ['instId' => '01', 'bin' => '8600'],
        ['instId' => '02', 'bin' => '9860'],
    ];

    $bins = BinInfo::collect($items);

    expect($bins)->toHaveCount(2);
    expect($bins[0])->toBeInstanceOf(BinInfo::class);
    expect($bins[0]->bin)->toBe('8600');
});

it('card info accepts holdamount and cashbackamount aliases', function (): void {
    $card = CardInfo::from([
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
        'holdamount' => 12,
        'cashbackamount' => 34,
    ]);

    expect($card->holdAmount)->toBe(12);
    expect($card->cashbackAmount)->toBe(34);
});

it('card info rejects missing required fields', function (): void {
    $data = [
        'id' => '1',
        'username' => 'user',
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
        'holdAmount' => 12,
        'cashbackAmount' => 34,
    ];

    expect(fn () => CardInfo::from($data))->toThrow(ResponseException::class);
});

it('card info rejects missing hold or cashback amounts', function (): void {
    $data = [
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
        'holdAmount' => 12,
    ];

    expect(fn () => CardInfo::from($data))->toThrow(ResponseException::class);
});

it('terminal info accepts terminal_type alias', function (): void {
    $terminal = TerminalInfo::from([
        'pid' => 1,
        'terminalId' => '91419475',
        'merchantId' => '90050017182',
        'username' => 'user',
        'terminal_type' => 2,
        'instId' => '01',
        'name' => 'Terminal',
        'port' => 443,
        'purpose' => 'DEFAULT',
    ]);

    expect($terminal->terminalType)->toBe(2);
});

it('terminal info accepts t_type alias', function (): void {
    $terminal = TerminalInfo::from([
        'pid' => 1,
        'terminalId' => '91419475',
        'merchantId' => '90050017182',
        'username' => 'user',
        't_type' => 3,
        'instId' => '01',
        'name' => 'Terminal',
        'port' => 443,
        'purpose' => 'DEFAULT',
    ]);

    expect($terminal->terminalType)->toBe(3);
});

it('terminal info rejects missing terminal type', function (): void {
    $data = [
        'pid' => 1,
        'terminalId' => '91419475',
        'merchantId' => '90050017182',
        'username' => 'user',
        'instId' => '01',
        'name' => 'Terminal',
        'port' => 443,
        'purpose' => 'DEFAULT',
    ];

    expect(fn () => TerminalInfo::from($data))->toThrow(ResponseException::class);
});

it('terminal info rejects missing required fields', function (): void {
    $data = [
        'pid' => 1,
        'terminalId' => '91419475',
        'merchantId' => '90050017182',
        'username' => 'user',
        'terminalType' => 2,
        'instId' => '01',
        'port' => 443,
        'purpose' => 'DEFAULT',
    ];

    expect(fn () => TerminalInfo::from($data))->toThrow(ResponseException::class);
});

it('terminal info collects valid items', function (): void {
    $items = [
        [
            'pid' => 1,
            'terminalId' => '91419475',
            'merchantId' => '90050017182',
            'username' => 'user',
            'terminalType' => 2,
            'instId' => '01',
            'name' => 'Terminal',
            'port' => 443,
            'purpose' => 'DEFAULT',
        ],
    ];

    $terminals = TerminalInfo::collect($items);

    expect($terminals)->toHaveCount(1);
    expect($terminals[0])->toBeInstanceOf(TerminalInfo::class);
});

it('transaction info accepts optional response fields', function (): void {
    $transaction = TransactionInfo::from([
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
        'field48' => 'extra-48',
        'field91' => 'extra-91',
        'merchantId' => '90050017182',
        'terminalId' => '91419475',
        'resp' => 0,
        'respText' => 'ok',
        'respSV' => '0',
        'status' => 'OK',
    ]);

    expect($transaction->field48)->toBe('extra-48');
    expect($transaction->field91)->toBe('extra-91');
    expect($transaction->respText)->toBe('ok');
});

it('cards.new.verify response rejects missing hold or cashback amounts', function (): void {
    $data = [
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
        'holdAmount' => 12,
    ];

    expect(fn () => NewVerifyResponse::from($data))->toThrow(ResponseException::class);
});

it('collect methods reject non-array items', function (): void {
    expect(fn () => BinInfo::collect(['bad']))->toThrow(ResponseException::class);
    expect(fn () => CardInfo::collect(['bad']))->toThrow(ResponseException::class);
    expect(fn () => TerminalInfo::collect(['bad']))->toThrow(ResponseException::class);
    expect(fn () => TransactionInfo::collect(['bad']))->toThrow(ResponseException::class);
});
