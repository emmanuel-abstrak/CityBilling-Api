<?php

namespace App\Models;

use App\Library\Enums\PropertyType;
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
        'type',
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

    public function getRatesChargeAttribute(): string | null
    {
        return match ($this->getAttribute('type')) {
            PropertyType::residential->value => money_currency($this->getAttribute('tariffGroup')->getAttribute('residential_rates_charge')),
            PropertyType::commercial->value => money_currency($this->getAttribute('tariffGroup')->getAttribute('commercial_rates_charge')),
            default => null
        };
    }

    public function getRefuseChargeAttribute(): string | null
    {
        return match ($this->getAttribute('type')) {
            PropertyType::residential->value => money_currency($this->getAttribute('tariffGroup')->getAttribute('residential_refuse_charge')),
            PropertyType::commercial->value => money_currency($this->getAttribute('tariffGroup')->getAttribute('commercial_refuse_charge')),
            default => null
        };
    }

    public function getSewerChargeAttribute(): string | null
    {
        return match ($this->getAttribute('type')) {
            PropertyType::residential->value => money_currency($this->getAttribute('tariffGroup')->getAttribute('residential_sewerage_charge')),
            PropertyType::commercial->value => money_currency($this->getAttribute('tariffGroup')->getAttribute('commercial_sewerage_charge')),
            default => null
        };
    }

    public function getBalancesAttribute(): array
    {
        $rates_balance = 0;
        $refuse_balance = 0;
        $sewer_balance = 0;

        $this->getOwingStatements()->each(function ($statement) use(&$rates_balance, &$refuse_balance, &$sewer_balance) {
            $rates_balance += ($statement->getAttribute('rates_total') - $statement->getAttribute('rates_paid'));
            $refuse_balance += ($statement->getAttribute('refuse_total') - $statement->getAttribute('refuse_paid'));
            $sewer_balance += ($statement->getAttribute('sewer_total') - $statement->getAttribute('sewer_paid'));
        });

        return [
            'rates' => money_currency($rates_balance),
            'refuse' => money_currency($refuse_balance),
            'sewer' => money_currency($sewer_balance)
        ];
    }

    private function getOwingStatements(): Collection
    {
        return $this->getAttribute('statements')->filter(function($statement) {
            return (
                $statement->getAttribute('rates_paid') < $statement->getAttribute('rates_total') ||
                $statement->getAttribute('refuse_paid') < $statement->getAttribute('refuse_total') ||
                $statement->getAttribute('sewer_paid') < $statement->getAttribute('sewer_total')
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
