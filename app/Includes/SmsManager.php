<?php

namespace App\Includes;

use App\Includes\Constant;
use App\Models\SmsType;
use Exception;
use Illuminate\Support\Facades\Log;

class SmsManager
{
    public static function sendSms($sms_type_name, $values, $from, $to)
    {
        
    }

    public static function calculateSmsCost($text, $phone_number)
    {
        // TODO calculate cost
        return 10;
    }

    public static function generateDefaultSmsTypes($tenant){
        $tenant->run(function () {
            foreach(Constant::$SMS_DEFAULT_TYPES as $key => $value){
                $st = new SmsType();
                $st->name = $key;
                $st->pattern = $value;
                $st->is_default = 1;
                $st->save();
            }
        });
    }
}
