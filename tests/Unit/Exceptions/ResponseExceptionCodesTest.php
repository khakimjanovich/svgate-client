<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Codes\RPCErrors;
use Khakimjanovich\SVGate\DTO\Cards\Get\CardInfo;
use Khakimjanovich\SVGate\DTO\Cards\NewVerify\Response as NewVerifyResponse;
use Khakimjanovich\SVGate\DTO\Terminals\Get\TerminalInfo;
use Khakimjanovich\SVGate\Exceptions\ResponseException;
use Khakimjanovich\SVGate\Validation\Attributes\ArrayOf;

it('missing field errors map to SDK_RESPONSE_MISSING_FIELD', function (): void {
    try {
        NewVerifyResponse::from(['id' => '1']);
        throw new RuntimeException('Expected ResponseException not thrown.');
    } catch (ResponseException $exception) {
        expect($exception->getCode())->toBe(RPCErrors::SDK_RESPONSE_MISSING_FIELD);
    }
});

it('invalid item errors map to SDK_RESPONSE_INVALID_ITEM', function (): void {
    try {
        CardInfo::collect(['bad']);
        throw new RuntimeException('Expected ResponseException not thrown.');
    } catch (ResponseException $exception) {
        expect($exception->getCode())->toBe(RPCErrors::SDK_RESPONSE_INVALID_ITEM);
    }

    try {
        TerminalInfo::collect(['bad']);
        throw new RuntimeException('Expected ResponseException not thrown.');
    } catch (ResponseException $exception) {
        expect($exception->getCode())->toBe(RPCErrors::SDK_RESPONSE_INVALID_ITEM);
    }
});

it('array of response mapping uses SDK_RESPONSE_INVALID_ITEM', function (): void {
    $rule = new ArrayOf(CardInfo::class);

    try {
        $rule->map(['bad'], 'cards', false, ResponseException::class);
        throw new RuntimeException('Expected ResponseException not thrown.');
    } catch (ResponseException $exception) {
        expect($exception->getCode())->toBe(RPCErrors::SDK_RESPONSE_INVALID_ITEM);
    }
});
