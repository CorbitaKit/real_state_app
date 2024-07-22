<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'user_id',
        'birth_day',
        'address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
