<?php


namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\SmsType;
use Illuminate\Http\Request;


class SmsTypesController extends BaseController
{
    public function createSmsType(Request $request)
    {
        $st = new SmsType();
        $st->name = $request->input('name');
        $st->pattern = $request->input('pattern');

        if(SmsType::find($st->name))
            return $this->sendResponse(Constant::$REPETITIVE_SMS_TYPE_NAME, null);

        $st->save();

        return $this->sendResponse(Constant::$SUCCESS,  ['sms_type_name' => $st->name]);
    }

    public function updateSmsType(Request $request)
    {
        $st = SmsType::find($request->input('name'));
        if(!$st) return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);

        $st->new_pattern = $request->input("pattern");
        $st->validation_status = Constant::$VALIDATION_STATUS_IS_CHECKING;
        $st->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteSmsType(Request $request)
    {
        $st = SmsType::find($request->input('name'));
        if(!$st) return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);
        if($st->is_default) return $this->sendResponse(Constant::$NOT_DELETABLE, null);

        $st->delete();
        
        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function fetchSmsTypes($all)
    {
        $st_list = ($all) ? SmsType::all() : SmsType::valid()->get();

        $result = $st_list->map(function ($st) {
            return [
                "name" => $st->name,
                "pattern" => $st->pattern,
                "is_default" => $st->is_default
            ];
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }
}
