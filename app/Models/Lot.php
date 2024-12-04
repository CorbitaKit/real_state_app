<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lot extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'property_id',
        'lot_group_id',
        'user_id',
        'color_label',
        'name',
        'block'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function lotGroup(): BelongsTo
    {
        return $this->belongsTo(LotGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function paymentPlans(): HasMany
    {
        return $this->hasMany(PaymentPlan::class);
    }
}
