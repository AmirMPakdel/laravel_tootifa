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
        $upload_transaction->upload_key = uniqid('', true);
        $upload_transaction->upload_type = $request->input('upload_type');
        $upload_transaction->file_type = $request->input('file_type');
        $upload_transaction->file_size = $request->input('file_size');
        $upload_transaction->old_upload_key = $request->input('old_upload_key');

        $course_id = $request->input('course_id');
        if (0/*$course_id*/) { //fix later - bad condition
            $course = Course::find($course_id);
            $upload_transaction->is_public = 0;
            $upload_transaction->is_encrypted = $course->is_encrypted;
            if ($course->is_encrypted) $upload_transaction->enc_key = Helper::generateKey(16);
        } else {
            $upload_transaction->is_public = 1;
            $upload_transaction->is_encrypted = 0;
        }

        $upload_transaction->status = Constant::$UPLOAD_TRANSACTION_STATUS_GENERATED;
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
}
