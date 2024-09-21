<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyType extends Model
{
    use HasFactory, TracksActivity;

    protected $fillable = [
        'name',
        'cutoff',
        'cutoff_price',
        'price'
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'type_id');
    }
}
