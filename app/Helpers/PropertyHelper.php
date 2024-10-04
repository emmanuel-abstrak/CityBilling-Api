<?php

namespace App\Helpers;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

class PropertyHelper
{
    public static function getOwingStatements(Property $property): Collection
    {
        return $property->getAttribute('statements')->filter(function($statement) {
            return (
                $statement->getAttribute('paid') < $statement->getAttribute('total')
            );
        });
    }

    public static function getBalances(Property $property): array
    {
        $data = [];
        $property->getAttribute('tariffGroup')->getAttribute('tariffs')
            ->where('property_type_id', $property->getAttribute('type_id'))->each(function($tariff) use (&$data) {
                $data[strtolower($tariff->getAttribute('service')->getAttribute('name'))] = [
                    'name' => strtolower($tariff->getAttribute('service')->getAttribute('name')),
                    'amount' => money_currency(0),
                    'total' => money_currency(0),
                ];
            });

        static::getOwingStatements($property)->each(function ($statement) use(&$data) {
            foreach ($statement->items as $statementItem) {
                $balance = ($statementItem->getAttribute('total') - $statementItem->getAttribute('paid'));
                $key = strtolower($statementItem->getAttribute('service')->getAttribute('name'));
                if (!isset($data[$key])) {
                    $data[$key] = [
                        'name' => $key,
                        'amount' => money_currency($balance),
                    ];
                } else {
                    $data[$key] = [
                        'name' => $key,
                        'amount' => money_currency($data[$key]['amount'] + $balance),
                    ];
                }
            }
        });

        return $data;
    }
}
