<?php

namespace App\ServiceProviders\Shared;

interface IServiceProvider
{
    public function lookup(string $meter) : ?MeterDetail;
    public function lookupNewMeter(string $meter) : ?MeterDetail;
    public function vend(string $meter, ?float $amount, ?float $volume) : ?TokenDetail;
}
