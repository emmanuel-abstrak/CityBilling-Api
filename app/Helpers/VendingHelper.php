<?php

namespace App\Helpers;

use App\Models\Currency;
use App\Models\Property;
use App\ServiceProviders\Shared\MeterDetail;

readonly class VendingHelper
{
    public static function getLookupSummary(MeterDetail $meterDetail, Currency $currency, float $amount): array
    {
        $property = $meterDetail->getProperty();
        $meterArray = $meterDetail->toArray();
        $waterCharge = static::getWaterCharge($property);
        $meterArray['price'] = $waterCharge * $currency->getAttribute('exchange_rate');
        $meterArray['currency'] = $currency->getAttribute('code');

        $balances = 0;
        foreach ($property->getAttribute('balances') as $balance) {
            $balances += $balance['amount'];
        }

        $newCurrencyAmount = $amount * $currency->getAttribute('exchange_rate');

        $returnData = [
            'amount' => $newCurrencyAmount,
            'balances' => [],
            'vat' => 0,
            'tokenAmount' => 0,
            'volume' => 0,
            'currency' => $currency->getAttribute('code'),
            'meter' => $meterArray
        ];
        $remainingAmount = $newCurrencyAmount;
        if($balances > 0) {
            $property->getOwingStatements()->each(function ($statement) use(&$remainingAmount, &$returnData) {
                foreach ($statement->items as $statementItem) {
                    $balance = ($statementItem->getAttribute('total') - $statementItem->getAttribute('paid'));
                    $key = strtolower($statementItem->getAttribute('service')->getAttribute('name'));
                    if ($remainingAmount > 0) {
                        $deduction = min($balance, $remainingAmount);
                        $remainingAmount -= $deduction;
                        if (!isset($returnData[$key])) {
                            $returnData['balances'][$key] = [
                                'name' => $key,
                                'amount' => $deduction,
                            ];
                        } else {
                            $returnData['balances'][$key] = [
                                'name' => $key,
                                'amount' => $returnData['balances'][$key]['amount'] + $deduction,
                            ];
                        }
                    }
                }
            });
        }

        if ($meterDetail->getVat() > 0 && $remainingAmount > 0) {
            $vat = ($meterDetail->getVat() * $remainingAmount) / 100;
            $returnData['vat'] = $vat;
            $remainingAmount -= $vat;
        }

        $returnData['tokenAmount'] = $remainingAmount;

        if ($remainingAmount > 0) {
            $returnData['volume'] = $remainingAmount / $meterDetail->getPrice();
        }

        $returnData['balances'] = array_values($returnData['balances']);

        return $returnData;
    }

    public static function getWaterCharge(Property $property)
    {
        $propertyType = $property->getAttribute('type');
        $currentMonthPurchaseVolume = WaterPurchaseHelper::getCurrentMonthVolume($property);

        if ($propertyType->cutoff) {
            if ($currentMonthPurchaseVolume < $propertyType->cutoff) {
                return $propertyType->cutoff_price;
            }
        }

        return $propertyType->price;
    }
}
