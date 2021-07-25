<?php

namespace App\Models;

use App\Includes\Constant;
use App\Includes\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Writer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'bio',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($writer) {
            Helper::uploadFileToDisk(
                Constant::$FILE_ACTION_DELETE,
                $writer,
                'image',
                'public',
                'images/writers',
                '.png',
                null
            );
        });
    }
}
