<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TariffGroup extends Model
{
    use HasFactory, TracksActivity;

    protected $fillable = [
        'suburb_id',
        'min_size',
        'max_size'
    ];

    public function suburb(): BelongsTo
    {
        return $this->belongsTo(Suburb::class, 'suburb_id');
    }

    public function tariffs(): HasMany
    {
        return $this->hasMany(TariffGroupCharge::class, 'tariff_group_id');
    }
}
