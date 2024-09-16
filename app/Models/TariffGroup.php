<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TariffGroup extends Model
{
    use HasFactory, TracksActivity;

    protected $fillable = [
        'suburb_id',
        'min_size',
        'max_size',
        'residential_rates_charge',
        'residential_refuse_charge',
        'residential_sewerage_charge',
        'commercial_rates_charge',
        'commercial_refuse_charge',
        'commercial_sewerage_charge'
    ];

    public function suburb(): BelongsTo
    {
        return $this->belongsTo(Suburb::class, 'suburb_id');
    }
}
