<?php

namespace App\Models;

use App\Helpers\PropertyHelper;
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

    public function waterPurchases(): HasMany
    {
        return $this->hasMany(WaterPurchase::class, 'property_id');
    }

    public function tariffGroup(): BelongsTo
    {
        return $this->belongsTo(TariffGroup::class, 'tariff_group_id');
    }

    public function getBalancesAttribute(): array
    {
        return PropertyHelper::getBalances($this);
    }

    public function getOwingStatements(): Collection
    {
        return PropertyHelper::getOwingStatements($this);
    }
}
