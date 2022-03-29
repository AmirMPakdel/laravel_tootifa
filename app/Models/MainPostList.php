<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainPostList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'list',
        'default_type',
    ];

    public function scopeVisible($query){
        return $query->where('visible', '=', 1);
    }
}
