<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterPurchase extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';
    const STATUS_ABANDONED = 'abandoned';

    protected $fillable = [
        'property_id',
        'currency_id',
        'status',
        'requested_amount',
        'token_amount',
        'price',
        'vat',
        'tariffs',
        'payment_method',
        'volume',
        'token',
        'redirect_url',
        'poll_url',
    ];

    protected $appends = ['formatted_tariffs'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getFormattedTariffsAttribute(): array
    {
        return json_decode($this->getAttribute('tariffs'), true);
    }
}
