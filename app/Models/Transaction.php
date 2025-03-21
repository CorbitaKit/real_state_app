<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'total_amount',
        'balance',
        'tcp'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
