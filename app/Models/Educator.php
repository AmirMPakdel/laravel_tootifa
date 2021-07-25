<?php

namespace App\Models;

use App\Includes\Constant;
use App\Includes\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Educator extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'bio',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($educator) {
            Helper::uploadFileToDisk(
                Constant::$FILE_ACTION_DELETE,
                $educator,
                'image',
                'public',
                'images/educators',
                '.png',
                null
            );
        });
    }

}
