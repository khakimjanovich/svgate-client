<?php

declare(strict_types=1);

use Khakimjanovich\SVGate\Configs\ClientOptions;
use Khakimjanovich\SVGate\SVGate;

if (! function_exists('svgate')) {
    function svgate(ClientOptions $config): SVGate
    {
        return new SVGate($config);
    }
}
