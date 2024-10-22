<?php

namespace App\Helpers;

use App\Models\Currency;
use App\Models\Property;
use App\ServiceProviders\Calin;
use App\ServiceProviders\Shared\MeterDetail;
use App\Models\WaterPurchase;
use App\ServiceProviders\Shared\TokenDetail;

readonly class VendingHelper
{
    public static function getLookupSummary(MeterDetail $meterDetail, Currency $currency, float $amount): array
    {
        $property = $meterDetail->getProperty();
        $meterArray = $meterDetail->toArray();
        $meterArray['currency'] = $currency->getAttribute('code');
        $meterArray['number'] = $property->getAttribute('meter');

        $balances = 0;
        foreach ($property->getAttribute('balances') as $balance) {
            $balances += round($balance['amount'], 2);
        }

        $newCurrencyAmount = $amount;

        $returnData = [
            'amount' => $newCurrencyAmount,
            'balances' => [],
            'vat' => 0,
            'tokenAmount' => 0,
            'volume' => 0,
            'currency' => $currency->getAttribute('code'),
            'currency_id' => $currency->getAttribute('id'),
            'property_id' => $property->getAttribute('id'),
            'meter' => $meterArray
        ];
        $remainingAmount = $newCurrencyAmount;
        if ($balances > 0) {
            $property->getOwingStatements()->each(function ($statement) use (&$remainingAmount, &$returnData, $currency) {
                foreach ($statement->items as $statementItem) {
                    $balance = ($statementItem->getAttribute('total') - $statementItem->getAttribute('paid'));
                    $key = strtolower($statementItem->getAttribute('service')->getAttribute('name'));
                    if ($remainingAmount > 0) {
                        $deduction = CurrencyHelper::convert($currency, min($balance, $remainingAmount));
                        $remainingAmount -= $deduction;
                        if (!isset($returnData[$key])) {
                            $returnData['balances'][$key] = [
                                'statement_item_id' => $statementItem->getAttribute('id'),
                                'name' => $key,
                                'amount' => round($deduction, 2),
                            ];
                        } else {
                            $returnData['balances'][$key] = [
                                'statement_item_id' => $statementItem->getAttribute('id'),
                                'name' => $key,
                                'amount' => round($returnData['balances'][$key]['amount'] + $deduction, 2),
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

        $returnData['tokenAmount'] = round($remainingAmount, 2);

        if ($remainingAmount > 0) {
            $returnData['volume'] = static::getWaterVolume($remainingAmount, $currency, $property);
        }

        $returnData['balances'] = array_values($returnData['balances']);
        $returnData['vat'] = round($returnData['vat'], 2);

        return $returnData;
    }

    public static function getWaterVolume(float $amount, Currency $currency, Property $property): float
    {
        $remainingAmount = round(CurrencyHelper::convert($currency, $amount), 2);
        $propertyType = $property->getAttribute('type');
        $currentMonthPurchaseVolume = WaterPurchaseHelper::getCurrentMonthVolume($property);

        $normalCharge = round(CurrencyHelper::convert($currency, $propertyType->price), 2);

        $normalVolume = 0;
        $discountedVolume = 0;

        if ($propertyType->cutoff) {
            $discountedCharge = round(CurrencyHelper::convert($currency, $propertyType->cutoff_price), 2);
            $remainingDiscountedVolume = max(0, $propertyType->cutoff - $currentMonthPurchaseVolume);
            $discountedVolume = min($remainingAmount / $discountedCharge, $remainingDiscountedVolume);
            $remainingAmount -= $discountedVolume * $discountedCharge;
        }

        if ($remainingAmount > 0) {
            $normalVolume = $remainingAmount / $normalCharge;
        }

        return $discountedVolume + $normalVolume;
    }

    public static function buyToken(WaterPurchase $purchase): ?TokenDetail
    {
        $calin = new Calin();
        return $calin->vend($purchase);
    }
}
