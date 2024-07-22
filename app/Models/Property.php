<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'province',
        'city',
        'barangay',
        'phase',
        'purok',
        'balance_payable',
        'down_payment'
    ];

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }

    public function lotGroups(): HasMany
    {
        return $this->hasMany(LotGroup::class);
    }
}
