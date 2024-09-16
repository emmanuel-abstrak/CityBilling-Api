<?php

namespace App\ServiceProviders;

use App\Library\Enums\MeterProvider;
use App\Models\Property;
use App\ServiceProviders\Shared\MeterDetail;
use App\ServiceProviders\Shared\TokenDetail;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Calin
{
    public static function lookup(?Property $property = null, ?string $meter = null): ?MeterDetail
    {
        $data = [
            'meter_number' => $property ? $property->getAttribute('meter') : $meter,
            'amount' => 0,
            'is_vend_by_unit' => true,
        ];

        $res = self::sendRequest($data);
        if (is_array($res) && isset($res['result']) && isset($res['result_code']) && $res['result_code'] == 0) {
            $data = $res['result'];

            $meterDetail = new MeterDetail();
            $meterDetail->setCustomerName($data['customer_name']);
            $meterDetail->setCustomerAddress($data['customer_addr']);
            $meterDetail->setPrice($data['price']);
            $meterDetail->setVat($data['vat']);
            $meterDetail->setCurrency($data['currency']);
            $meterDetail->setUnit($data['unit']);
            $meterDetail->setProvider(MeterProvider::calin->value);

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
            'meter_number' => $property->getAttribute('meter'),
            'amount' => $amount,
            'is_vend_by_unit' => true,
        ];

        $res = self::sendRequest($data);
        if (is_array($res) && isset($res['result']) && isset($res['result_code']) && $res['result_code'] == 0) {
            return new TokenDetail();
        }

        return null;
    }

    private static function sendRequest(array $data): mixed
    {
        $data = array_merge([
            'company_name' => config('providers.calin.company'),
            'user_name' => config('providers.calin.username'),
            'password' => config('providers.calin.password'),
            'password_vend' => config('providers.calin.vendor')
        ],$data);

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $request = new Request('POST', config('providers.calin.base_url'). '/POS_Purchase', $headers, json_encode($data));
        $result = (new Client())->sendAsync($request)->wait();
        if ($result->getStatusCode() == 200) {
            return json_decode($result->getBody()->getContents(), true);
        }

        return false;
    }
}
