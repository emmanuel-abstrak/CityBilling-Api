<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PropertyStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'total',
        'paid'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PropertyStatementItem::class, 'property_statement_id');
    }
}
