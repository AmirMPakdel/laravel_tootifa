<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'size',
    ];

    public function ContentDocumnetable()
    {
        return $this->morphTo();
    }
}
