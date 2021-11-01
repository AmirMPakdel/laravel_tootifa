<?php


namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\Course;
use App\Models\UploadTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Psy\debug;

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
        if ($course_id) {
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

    public function updateFileState($file_state, $tenant, $is_public, $model, $attr, $uk)
    {
        // Check for upload key
        $upload_transaction = UploadTransaction::where('upload_key', $uk)->first();

        if ($file_state != Constant::$UPDATE_FILE_STATE_NO_CHANGE) {
            // Send invalid uk if it is null or wrong
            if (!$upload_transaction) return Constant::$INVALID_UPLOAD_KEY;

            if ($file_state == Constant::$UPDATE_FILE_STATE_NEW || $file_state == Constant::$UPDATE_FILE_STATE_REPLACE) {
                // move file to ftp
                $this->moveFileToFtp($uk, $tenant, $is_public, $upload_transaction->file_type, null);

                if ($file_state == Constant::$UPDATE_FILE_STATE_REPLACE) {
                    // check for old ut
                    $old_upload_transaction = UploadTransaction::where('upload_key', $upload_transaction->old_upload_key)->first();
                    if (!$old_upload_transaction) return Constant::$INVALID_OLD_UPLOAD_KEY;

                    // change old upload transaction status
                    $old_upload_transaction->status = Constant::$UPLOAD_TRANSACTION_STATUS_DELETED;
                    $old_upload_transaction->save();

                    // delete old from ftp
                    $this->deleteFileFromFtp(
                        $upload_transaction->old_upload_key,
                        $tenant
                    );
                }
                $model[$attr] = $uk;
            }

            Log::debug("tenant in update: " . $tenant);

            if ($file_state == Constant::$UPDATE_FILE_STATE_DELETE) {
                // delete from ftp
                $this->deleteFileFromFtp($uk, $tenant);
                $model[$attr] = null;
            }

            $model->save();
        }

        return Constant::$SUCCESS;
    }

    public function moveFileToFtp($upload_key, $tenant, $is_public, $type, $enc_key)
    {
        $upload_transaction = UploadTransaction::where('upload_key', $upload_key)->first();
        if (!$upload_transaction) return $this->sendResponse(Constant::$INVALID_UPLOAD_KEY, null);

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
                $upload_transaction->status = Constant::$UPLOAD_TRANSACTION_STATUS_FTP;
                $upload_transaction->save();

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

    public function deleteFileFromFtp($upload_key, $tenant)
    {
        $upload_transaction = UploadTransaction::where('upload_key', $upload_key)->first();
        if (!$upload_transaction) return $this->sendResponse(Constant::$INVALID_UPLOAD_KEY, null);

        $url = "http://tootifa.ir:8020/delete_file";

        $fields = [
            'upload_key'         => $upload_key,
            'tenant '      => $tenant,
        ];

        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            die('Couldn\'t send request: ' . curl_error($ch));
        } else {
            $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($resultStatus == 200) {
                $upload_transaction->status = Constant::$UPLOAD_TRANSACTION_STATUS_DELETED;
                $upload_transaction->save();

                Log::debug("tenant in delete: " . $tenant);
                Log::debug($result);
                return ["result" => $result, "is_deleted" => true];
            } else {
                die('Request failed: HTTP status code: ' . $resultStatus);
            }
        }

        curl_close($ch);
        return ["result" => $result, "is_deleted" => false];
    }
}
