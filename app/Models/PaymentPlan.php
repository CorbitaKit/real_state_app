<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentPlan extends Model
{
    use HasFactory;

    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id');
    }

    public function lot()
    {
        return $this->hasOne(Lot::class, 'id', 'lot_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
