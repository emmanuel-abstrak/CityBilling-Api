<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyStatementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_statement_id',
        'service_id',
        'total',
        'paid'
    ];

    public function propertyStatement(): BelongsTo
    {
        return $this->belongsTo(PropertyStatement::class, 'property_statement_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
