<?php

namespace App\Models;

use App\ServiceProviders\Shared\MeterDetail;
use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory, TracksActivity;

    public array $deductionOrder = ['rates', 'refuse', 'sewer'];

    protected $fillable = [
        'owner_id',
        'type_id',
        'suburb_id',
        'tariff_group_id',
        'size',
        'meter',
        'meter_provider',
        'address'
    ];

    protected $appends = [
        'rates_charge',
        'refuse_charge',
        'sewer_charge',
        'balances',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'type_id');
    }

    public function suburb(): BelongsTo
    {
        return $this->belongsTo(Suburb::class, 'suburb_id');
    }

    public function statements(): HasMany
    {
        return $this->hasMany(PropertyStatement::class, 'property_id');
    }

    public function tariffGroup(): BelongsTo
    {
        return $this->belongsTo(TariffGroup::class, 'tariff_group_id');
    }

    public function getBalancesAttribute(): array
    {
        $data = [];
        $this->getAttribute('tariffGroup')->getAttribute('tariffs')
            ->where('property_type_id', $this->getAttribute('type_id'))->each(function($tariff) use (&$data) {
                $data[strtolower($tariff->getAttribute('service')->getAttribute('name'))] = [
                    'name' => strtolower($tariff->getAttribute('service')->getAttribute('name')),
                    'amount' => money_currency(0)
                ];
            });

        $this->getOwingStatements()->each(function ($statement) use(&$data) {
            foreach ($statement->items as $statementItem) {
                $balance = ($statementItem->getAttribute('total') - $statementItem->getAttribute('paid'));
                $key = strtolower($statementItem->getAttribute('service')->getAttribute('name'));
                if (!isset($data[$key])) {
                    $data[$key] = [
                        'name' => $key,
                        'amount' => money_currency($balance)
                    ];
                } else {
                    $data[$key] = [
                        'name' => $key,
                        'amount' => money_currency($data[$key]['amount'] + $balance)
                    ];
                }
            }
        });

        return $data;
    }

    private function getOwingStatements(): Collection
    {
        return $this->getAttribute('statements')->filter(function($statement) {
            return (
                $statement->getAttribute('paid') < $statement->getAttribute('total')
            );
        });
    }

    public function getLookupSummary(MeterDetail $meterDetail, string $amount, Currency $currency): array
    {
        $balances = $this->getAttribute('balances');

        $newCurrencyAmount = $amount * $currency->getAttribute('exchange_rate');

        $returnData = [
            'amount' => $newCurrencyAmount,
            'rates' => 0,
            'refuse' => 0,
            'sewer' => 0,
            'vat' => 0,
            'tokenAmount' => 0,
            'currency' => $currency->getAttribute('code')
        ];
        $remainingAmount = $newCurrencyAmount;
        if(array_sum($balances) > 0) {

            $this->getOwingStatements()->each(function ($statement) use(&$remainingAmount, &$returnData, $currency) {
                if ($remainingAmount > 0) {
                    $ratesBalance = ($statement->getAttribute('rates_total') - $statement->getAttribute('rates_paid')) * $currency->getAttribute('exchange_rate');
                    if ($ratesBalance > 0) {
                        $returnData['rates'] += min($ratesBalance, $remainingAmount);
                        $remainingAmount = max(($remainingAmount - $ratesBalance), 0);
                    }
                }

                if ($remainingAmount > 0) {
                    $ratesBalance = ($statement->getAttribute('refuse_total') - $statement->getAttribute('refuse_paid')) * $currency->getAttribute('exchange_rate');
                    if ($ratesBalance > 0) {
                        $returnData['refuse'] += min($ratesBalance, $remainingAmount);
                        $remainingAmount = max(($remainingAmount - $ratesBalance), 0);
                    }
                }

                if ($remainingAmount > 0) {
                    $ratesBalance = ($statement->getAttribute('sewer_total') - $statement->getAttribute('sewer_paid')) * $currency->getAttribute('exchange_rate');
                    if ($ratesBalance > 0) {
                        $returnData['sewer'] += min($ratesBalance, $remainingAmount);
                        $remainingAmount = max(($remainingAmount - $ratesBalance), 0);
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

        $finalData = [];
        foreach ($returnData as $key => $item) {
            $finalData[$key] = $key != 'currency' ? money_currency($item) : $item;
        }

        return $finalData;
    }

    public function getVendingSummary(MeterDetail $meterDetail, string $amount, Currency $currency): array
    {
        $balances = $this->getAttribute('balances');
        $vat = $meterDetail->getVat();

        $newAmount = $amount * $currency->getAttribute('exchange_rate');
        $remainingAmount = $newAmount;
        if(array_sum($balances) > 0) {
            $quit = $remainingAmount > 0;
            while ($quit) {
                $this->getOwingStatements()->sort()->each(function ($statement) use(&$remainingAmount, &$quit) {
                    $this->doDeductions($statement, $remainingAmount, $quit);
                });
            }
        }

        return [$newAmount, $remainingAmount, $balances];
    }

    private function doDeductions(PropertyStatement &$statement, float &$remainingBalance, bool &$quit)
    {

    }
}
