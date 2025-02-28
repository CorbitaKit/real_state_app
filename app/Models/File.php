<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['filename', 'fileable_type', 'fileable_id', 'url', 'type'];

    public function fileable()
    {
        return $this->morphTo();
    }
}
