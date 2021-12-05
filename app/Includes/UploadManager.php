<?php

namespace App\Includes;

use App\Includes\Constant;
use App\Models\UploadTransaction;
use Exception;
use Illuminate\Support\Facades\Log;

class UploadManager
{
    public static function saveFile($tenant, $is_public, $model, $attr, $set_size, $set_key, $uk)
    {
        $upload_transaction = UploadTransaction::where('upload_key', $uk)->first();
        if ($uk && trim($uk) != "" && !$upload_transaction) return Constant::$INVALID_UPLOAD_KEY;

        if ($upload_transaction) {
            $result = UploadManager::moveFileToFtp(
                $uk,
                $tenant,
                $is_public,
                $upload_transaction->file_type,
                $upload_transaction->enc_key
            );

            if($result['url'] == null) return Constant::$CONVERTOR_SERVER_ISSUE_MOVING_FILE;

            $model[$attr] = $uk;
            if ($set_size) try {
                $model['size'] = $upload_transaction->file_size;
            } catch (Exception $e) {
                Log::error("NO SIZE ATTR");
            };

            if ($set_key) try {
                $model['encoding'] = $upload_transaction->is_encrypted;
                $model['key'] = $upload_transaction->enc_key;
            } catch (Exception $e) {
                Log::error("NO ENCODING ATTR");
            };

            $model->save();
        }

        return Constant::$SUCCESS;
    }

    public static function updateFileState($file_state, $tenant, $is_public, $model, $attr, $set_size, $set_key, $uk)
    {
        // Check for upload key
        $upload_transaction = UploadTransaction::where('upload_key', $uk)->first();
        if ($file_state !== Constant::$UPDATE_FILE_STATE_NO_CHANGE &&
            !$upload_transaction) return Constant::$INVALID_UPLOAD_KEY;

        if ($file_state != Constant::$UPDATE_FILE_STATE_NO_CHANGE) {
            // Send invalid uk if it is null or wrong
            if (!$upload_transaction) return Constant::$INVALID_UPLOAD_KEY;

            if ($file_state == Constant::$UPDATE_FILE_STATE_NEW || $file_state == Constant::$UPDATE_FILE_STATE_REPLACE) {
                // move file to ftp
                $result = UploadManager::moveFileToFtp(
                    $uk,
                    $tenant,
                    $is_public,
                    $upload_transaction->file_type,
                    $upload_transaction->enc_key
                );
                if(!$result['url']) return Constant::$CONVERTOR_SERVER_ISSUE_MOVING_FILE;

                if ($file_state == Constant::$UPDATE_FILE_STATE_REPLACE) {
                    // check for old ut
                    $old_upload_transaction = UploadTransaction::where('upload_key', $upload_transaction->old_upload_key)->first();
                    if (!$old_upload_transaction) return Constant::$INVALID_OLD_UPLOAD_KEY;

                    // change old upload transaction status
                    $old_upload_transaction->status = Constant::$UPLOAD_TRANSACTION_STATUS_DELETED;
                    $old_upload_transaction->save();

                    // delete old from ftp
                    $result = UploadManager::deleteFileFromFtp(
                        $upload_transaction->old_upload_key,
                        $tenant
                    );
                    if(!$result['is_deleted']) return Constant::$CONVERTOR_SERVER_ISSUE_DELETING_FILE;

                }
                $model[$attr] = $uk;
                if ($set_size) try {
                    $model['size'] = $upload_transaction->file_size;
                } catch (Exception $e) {
                    Log::error("NO SIZE ATTR");
                };

                if ($set_key) try {
                    $model['encoding'] = $upload_transaction->is_encrypted;
                    $model['key'] = $upload_transaction->enc_key;
                } catch (Exception $e) {
                    Log::error("NO ENCODING ATTR");
                };
            }

            if ($file_state == Constant::$UPDATE_FILE_STATE_DELETE) {
                // delete from ftp
                $result = UploadManager::deleteFileFromFtp($uk, $tenant);
                if(!$result['is_deleted']) return Constant::$CONVERTOR_SERVER_ISSUE_DELETING_FILE;

                $model[$attr] = null;
                if ($set_size) try {
                    $model['size'] = null;
                } catch (Exception $e) {
                    Log::error("NO SIZE ATTR");
                };

                if ($set_key) try {
                    $model['encoding'] = null;
                    $model['key'] = null;
                } catch (Exception $e) {
                    Log::error("NO ENCODING ATTR");
                };
            }

            $model->save();
        }

        return Constant::$SUCCESS;
    }

    public static function deleteFile($tenant, $uk)
    {
        $upload_transaction = UploadTransaction::where('upload_key', $uk)->first();
        if (!$upload_transaction) return Constant::$INVALID_UPLOAD_KEY;

        $result = UploadManager::deleteFileFromFtp($uk, $tenant);
        return ($result['is_deleted']) ? Constant::$SUCCESS: Constant::$CONVERTOR_SERVER_ISSUE_DELETING_FILE;
    }

    public static function moveFileToFtp($upload_key, $tenant, $is_public, $type, $enc_key)
    {
        $upload_transaction = UploadTransaction::where('upload_key', $upload_key)->first();
        if (!$upload_transaction) return ["result" => null, "url" => null];

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

        Log::info("FILE MOVED TO FTP: " . $result);

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

    public static function deleteFileFromFtp($upload_key, $tenant)
    {
        $upload_transaction = UploadTransaction::where('upload_key', $upload_key)->first();
        if (!$upload_transaction) return ["result" => null, "is_deleted" => false];

        $url = "http://tootifa.ir:8020/delete_file";

        $fields = [
            'upload_key'         => $upload_key,
            'tenant'      => $tenant,
        ];

        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        Log::info("FILE DELETED FROM FTP: " . $result);

        if (curl_errno($ch)) {
            die('Couldn\'t send request: ' . curl_error($ch));
        } else {
            $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($resultStatus == 200) {
                $upload_transaction->status = Constant::$UPLOAD_TRANSACTION_STATUS_DELETED;
                $upload_transaction->save();

                return ["result" => $result, "is_deleted" => true];
            } else {
                die('Request failed: HTTP status code: ' . $resultStatus);
            }
        }

        curl_close($ch);
        return ["result" => $result, "is_deleted" => false];
    }
}
