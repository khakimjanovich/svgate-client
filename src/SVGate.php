<?php

declare(strict_types=1);

namespace Khakimjanovich\SVGate;

use Khakimjanovich\SVGate\Configs\ClientOptions;
use Khakimjanovich\SVGate\Internal\JsonRpcCaller;
use Khakimjanovich\SVGate\Internal\Redactor;
use Khakimjanovich\SVGate\Services\Bins;
use Khakimjanovich\SVGate\Services\Cards;
use Khakimjanovich\SVGate\Services\Hold;
use Khakimjanovich\SVGate\Services\P2p;
use Khakimjanovich\SVGate\Services\Terminals;
use Khakimjanovich\SVGate\Services\Trans;

final class SVGate
{
    private JsonRpcCaller $caller;

    private ?Bins $bins = null;

    private ?Cards $cards = null;

    private ?Hold $hold = null;

    private ?P2p $p2p = null;

    private ?Terminals $terminals = null;

    private ?Trans $trans = null;

    public function __construct(private readonly ClientOptions $config)
    {
        $this->caller = new JsonRpcCaller(
            $config->endpoint,
            $config->username,
            $config->password,
            $config->httpClient,
            $config->requestFactory,
            $config->streamFactory,
            $config->logger,
            new Redactor
        );
    }

    public function cards(): Cards
    {
        if ($this->cards === null) {
            $this->cards = new Cards($this->caller);
        }

        return $this->cards;
    }

    public function bins(): Bins
    {
        if ($this->bins === null) {
            $this->bins = new Bins($this->caller);
        }

        return $this->bins;
    }

    public function terminals(): Terminals
    {
        if ($this->terminals === null) {
            $this->terminals = new Terminals($this->caller);
        }

        return $this->terminals;
    }

    public function p2p(): P2p
    {
        if ($this->p2p === null) {
            $this->p2p = new P2p($this->caller);
        }

        return $this->p2p;
    }

    public function trans(): Trans
    {
        if ($this->trans === null) {
            $this->trans = new Trans($this->caller);
        }

        return $this->trans;
    }

    public function hold(): Hold
    {
        if ($this->hold === null) {
            $this->hold = new Hold($this->caller);
        }

        return $this->hold;
    }
}
