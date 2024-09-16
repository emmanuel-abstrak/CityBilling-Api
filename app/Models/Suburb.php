<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suburb extends Model
{
    use HasFactory, TracksActivity;

    protected $fillable = [
        'name'
    ];

    public function tariffGroups(): HasMany
    {
        return $this->hasMany(TariffGroup::class, 'suburb_id');
    }
}
