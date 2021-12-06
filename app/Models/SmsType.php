<?php

namespace App\Models;

use App\Includes\Constant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsType extends Model
{
    use HasFactory;

    protected $primaryKey = 'name';

    public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';

    public function scopeValid($query){
        return $query->where('validation_status', '=', Constant::$VALIDATION_STATUS_VALID);
    }
}
