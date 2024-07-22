<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class WorkInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_address',
        'company_email',
        'company_number',
        'length_of_stay',
        'status',
        'user_id',
        'gross_monthly_income',
        'job_title',
        'position'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
