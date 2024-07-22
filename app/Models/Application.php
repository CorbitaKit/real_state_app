<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Observers\ApplicationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([ApplicationObserver::class])]
class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_type',
        'lot_id',
        'reserved_date',
        'user_id',
        'reason',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }
}
