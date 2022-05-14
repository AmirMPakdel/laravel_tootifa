<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\Course;
use App\Models\UploadTransaction;
use Illuminate\Http\Request;

class UploadController extends BaseController
{
    public function generateUploadKey(Request $request)
    {
        $upload_transaction = new UploadTransaction();
        $upload_transaction->upload_type = $request->input('upload_type');
        # $upload_transaction->upload_key = uniqid('', true)."-".$request->input('file_type');
        $upload_transaction->file_type = $request->input('file_type');
        $upload_transaction->file_size = $request->input('file_size');
        $upload_transaction->old_upload_key = $request->input('old_upload_key');

        if (in_array($upload_transaction->upload_type, Constant::getCourseItemsUploadTypes())) {
            $upload_transaction->is_public = 0;
            if (in_array($upload_transaction->upload_type, Constant::getCourseEncryptUploadTypes())){
                $course = Course::find($request->input('course_id'));
                if (!$course) return $this->sendResponse(Constant::$COURSE_NOT_FOUND, null);
                
                $upload_transaction->is_encrypted = $course->is_encrypted;
                if ($course->is_encrypted) $upload_transaction->enc_key = Helper::generateKey(16);
            }
        } else {
            $upload_transaction->is_public = 1;
            $upload_transaction->is_encrypted = 0;
        }

        $upload_transaction->status = Constant::$UPLOAD_TRANSACTION_STATUS_GENERATED;
        $upload_transaction->upload_key = $this->createUniqueUploadKey(
            $upload_transaction->upload_type,
            $upload_transaction->is_public,
            $upload_transaction->is_encrypted,
            $request->user->id
        );
        $upload_transaction->save();

        if ($request->input('old_upload_key')) {
            $old_upload_transaction = UploadTransaction::where('upload_key', $request->input('old_upload_key'))->first();
            $old_upload_transaction->status = Constant::$UPLOAD_TRANSACTION_STATUS_UPDATING;
            $old_upload_transaction->save();
        }

        $result = [
            'upload_key' => $upload_transaction->upload_key,
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function verifyUploadKey(Request $request)
    {
        $upload_transaction = UploadTransaction::where('upload_key', $request->input('upload_key'))->first();
        if (!$upload_transaction) return $this->sendResponse(Constant::$INVALID_UPLOAD_KEY, null);

        $upload_transaction->status = Constant::$UPLOAD_TRANSACTION_STATUS_VERIFIED;
        $upload_transaction->save();

        $result = [
            'file_type' => $upload_transaction->file_type,
            'file_size' => $upload_transaction->file_size,
            'is_encrypted' => $upload_transaction->is_encrypted,
            'is_public' => $upload_transaction->is_public
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }
    
    public function createUniqueUploadKey($type, $is_public, $is_encrypted, $user_id){

        if($is_public){
            $is_public = 1;
        }else{
            $is_public = 0;
        }
        if($is_encrypted){
            $is_encrypted = 1;
        }else{
            $is_encrypted = 0;
        }

        $random_str = md5(bin2hex(random_bytes(32)));

        $type_part = false;

        $type_map = array(
            "mp4"=>"a",
            "png"=>"b",
            "mp3"=>"c",
            "jpg"=>"d",
            "svg"=>"e",
            "pdf"=>"f",
            "gif"=>"g",
            "ogg"=>"h"
        );

        foreach ($type_map as $type_name => $type_char){
            if($type === $type_name){
                $type_part = $type_char;
            }
        }

        if(!$type_part){
            return "$type=>".$type;
            //return false;
        }

        $tenant_part = dechex($user_id);

        if(!$tenant_part){
            return "$user_id=>".$user_id;
            //return false;
        }

        return $tenant_part."-".$type_part.strval($is_public).strval($is_encrypted)."-".$random_str;
    }
}
