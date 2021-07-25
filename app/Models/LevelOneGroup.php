<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelOneGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function level_two_groups(){
        return $this->hasMany(LevelTwoGroup::class);
    }

    public function level_three_groups()
    {
        return $this->hasManyThrough(LevelThreeGroup::class, LevelTwoGroup::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($levelOneGroup) {
            $levelOneGroup->level_three_groups()->delete();
            $levelOneGroup->level_two_groups()->delete();
        });
    }
}
