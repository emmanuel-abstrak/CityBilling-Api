<?php

namespace App\ServiceProviders\Shared;

use App\Library\Enums\MeterProvider;
use App\Models\Property;
use App\Repositories\Properties\IPropertyRepository;
use App\ServiceProviders\Calin;
use App\ServiceProviders\Stron;
use App\ServiceProviders\Wuxi;

readonly class BaseServiceProvider implements IServiceProvider
{
    public function __construct(private IPropertyRepository $propertyRepository){}

    public function lookup(string $meter): ?MeterDetail
    {
        if ($property = $this->getMeterProperty($meter)) {
            return match ($property->getAttribute('meter_provider')) {
                MeterProvider::calin->value => Calin::lookup(property: $property),
                MeterProvider::stron->value => Stron::lookup(property: $property),
                MeterProvider::wuxi->value => Wuxi::lookup(property: $property),
                default => null
            };
        }

        return null;
    }

    public function lookupNewMeter(string $meter): ?MeterDetail
    {
        if ($meterDetails = Calin::lookup(meter: $meter)) {
            return $meterDetails;
        }
        if ($meterDetails = Stron::lookup(meter: $meter)) {
            return $meterDetails;
        }
        if ($meterDetails = Wuxi::lookup(meter: $meter)) {
            return $meterDetails;
        }

        return null;
    }

    public function vend(string $meter, ?float $amount, ?float $volume): ?TokenDetail
    {
        if($property = $this->getMeterProperty($meter)) {
            return match ($property->getAttribute('meter_provider')) {
                MeterProvider::calin->value => Calin::vend($property, $amount, $volume),
                MeterProvider::stron->value => Stron::vend($property, $amount, $volume),
                default => null
            };
        }

        return null;
    }

    private function getMeterProperty(string $meter): ?Property
    {
        return $this->propertyRepository->getByMeter($meter);
    }
}
