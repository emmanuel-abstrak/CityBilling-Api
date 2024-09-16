<?php

namespace App\ServiceProviders;

use App\Library\Enums\MeterProvider;
use App\Models\Property;
use App\ServiceProviders\Shared\MeterDetail;
use App\ServiceProviders\Shared\TokenDetail;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Stron
{
    public static function lookup(?Property $property = null, ?string $meter = null): ?MeterDetail
    {
        $data = [
            "MeterId" => $property ? $property->getAttribute('meter') : $meter,
            "CompanyName" => config('providers.stron.company'),
            "UserName" => config('providers.stron.username'),
            "PassWord" => config('providers.stron.password')
        ];

        $result = self::sendRequest("/QueryMeterInfo", $data);

        if (is_array($result) && $result['Meter_id']) {
            $meterDetail = new MeterDetail();
            $meterDetail->setCustomerName($result['Customer_name']);
            $meterDetail->setCustomerAddress($result['Customer_address']);
            $meterDetail->setCustomerPhone($result['Customer_phone']);
            $meterDetail->setPrice($result['Price']);
            $meterDetail->setVat($result['Rate']);
            $meterDetail->setCurrency($result['Price_unit']);
            $meterDetail->setUnit($result['Unit']);
            $meterDetail->setProvider(MeterProvider::stron->value);

            if ($property) {
                $meterDetail->setProperty($property);
            }

            return $meterDetail;
        }

        return null;
    }
    public static function vend(Property $property, ?float $amount, ?float $volume): ?TokenDetail
    {
        $data = [
            "CompanyName" => config('providers.stron.company'),
            "UserName" => config('providers.stron.username'),
            "PassWord" => config('providers.stron.password'),
            "MeterID" => $property->getAttribute('meter'),
            "is_vend_by_unit" => "false",
            "Amount" => $amount
        ];

        $result = self::sendRequest("/VendingMeter", $data);

        return new TokenDetail();
    }

    public static function sendRequest(string $path, ?array $data): mixed
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $request = new Request('POST', config('providers.stron.base_url'). $path, $headers, json_encode($data));
        $result = (new Client())->sendAsync($request)->wait();
        if ($result->getStatusCode() == 200) {
            $data = json_decode($result->getBody()->getContents(), true);
            return (isset($data[0]) && is_array($data[0])) ? $data[0] : $data;
        }

        return null;
    }
}
