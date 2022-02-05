<?php

namespace App\Models;

use App\Includes\Constant;
use App\Includes\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingPortal extends Model
{
    use HasFactory;
    
    public static function boot() {
        parent::boot();

        static::deleting(function($banking_portal) {
            Helper::uploadFileToDisk(
                Constant::$FILE_ACTION_DELETE,
                $banking_portal,
                'logo',
                'public',
                'images/portal-logos',
                '.png',
                null
            );
        });
    }
}
