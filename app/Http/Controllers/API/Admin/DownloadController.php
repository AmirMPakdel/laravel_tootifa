<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\UploadTransaction;
use Illuminate\Http\Request;

class DownloadController extends BaseController
{
    public function verifyUserForDownloadCourseItem(Request $request)
    {   
        $upload_transaction = UploadTransaction::where('upload_key', $request->input('upload_key'))->first();
        if(!$upload_transaction) return $this->sendResponse(Constant::$INVALID_UPLOAD_KEY, null);

        $result = [
            'file_type' => $upload_transaction->file_type
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }
}
