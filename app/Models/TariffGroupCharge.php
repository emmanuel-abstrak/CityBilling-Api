<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TariffGroupCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'tariff_group_id',
        'property_type_id',
        'service_id',
        'price'
    ];

    public function tariffGroup(): BelongsTo
    {
        return $this->belongsTo(TariffGroup::class, 'tariff_group_id');
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
