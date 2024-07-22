<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LotGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'color_label',
        'sqr_meter',
        'monthly_amortizations',
        'amount_per_sqr_meter'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }
}
