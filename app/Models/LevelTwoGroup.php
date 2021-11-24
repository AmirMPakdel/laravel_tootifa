<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelTwoGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function scopeType($query, $value){
        return $query->where('type', '=', $value);
    }

    public function level_one_group(){
        return $this->belongsTo(LevelOneGroup::class);
    }

    public function level_three_groups(){
        return $this->hasMany(LevelThreeGroup::class);
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

        static::deleting(function($levelTwoGroup) {
            $levelTwoGroup->level_three_groups()->delete();
        });
    }
}
