<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'size',
        'encoding',
        'key',
    ];

    public function ContentVideoable()
    {
        return $this->morphTo();
    }
}
