<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'rates_total',
        'refuse_total',
        'sewer_total',
        'rates_paid',
        'refuse_paid',
        'sewer_paid',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
