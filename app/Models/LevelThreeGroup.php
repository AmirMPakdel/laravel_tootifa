<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelThreeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function level_two_group(){
        return $this->belongsTo(LevelTwoGroup::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
