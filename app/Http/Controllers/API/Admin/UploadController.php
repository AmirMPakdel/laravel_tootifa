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

        $course_id = $request->input('course_id');
        if ($course_id) {
            $course = Course::find($course_id);
            $upload_transaction->is_public = 0;
            $upload_transaction->is_encrypted = $course->is_encrypted;
            if ($course->is_encrypted) $upload_transaction->enc_key = Helper::generateKey(16);
        } else {
            $upload_transaction->is_public = 1;
            $upload_transaction->is_encrypted = 0;
        }

        $upload_transaction->save();

        $result = [
            'upload_key' => $upload_transaction->upload_key,
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function verifyUploadKey(Request $request)
    {
        $upload_transaction = UploadTransaction::where('upload_key', $request->input('upload_key'))->first();
        if (!$upload_transaction) return $this->sendResponse(Constant::$INVALID_UPLOAD_KEY, null);

        $result = [
            'file_type' => $upload_transaction->file_type,
            'file_size' => $upload_transaction->file_size,
            'is_encrypted' => $upload_transaction->is_encrypted,
            'is_public' => $upload_transaction->is_public
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function moveFileToFtp($upload_key, $tenant, $is_public, $type, $enc_key)
    {
        $url = "http://localhost:8020/moveToFtp";

        $fields = [
            'upload_key'         => $upload_key,
            'tenant '      => $tenant,
            'enc_key' => $enc_key
        ];

        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            // this would be your first hint that something went wrong
            die('Couldn\'t send request: ' . curl_error($ch));
        } else {
            // check the HTTP status code of the request
            $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($resultStatus == 200) {
                // everything went better than expected
                $destination = ($is_public) ? "/public_files/{$tenant}/{$upload_key}.{$type}" :
                    "/{$tenant}/{$upload_key}.{$type}";

                return ["result" => $result, "url" => $destination];
            } else {
                // the request did not complete as expected. common errors are 4xx
                // (not found, bad request, etc.) and 5xx (usually concerning
                // errors/exceptions in the remote script execution)

                die('Request failed: HTTP status code: ' . $resultStatus);
            }
        }

        curl_close($ch);
        return ["result" => $result, "url" => null];    
    }
}
