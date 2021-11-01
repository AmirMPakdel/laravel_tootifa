<?php


namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\Educator;
use App\Models\UploadTransaction;
use Illuminate\Http\Request;


class EducatorsController extends BaseController
{
    public function createEducator(Request $request)
    {
        $educator = new Educator();
        $educator->first_name = $request->input("first_name");
        $educator->last_name = $request->input("last_name");
        $educator->bio = $request->input("bio");

        $uk = $request->input('upload_key');
        $upload_transaction = UploadTransaction::where('upload_key', $uk)->first();
        if ($upload_transaction) {
            $uc = new UploadController();
            $uc->moveFileToFtp($uk, tenant()->id, 1, $upload_transaction->file_type, null);
            $educator->image = $uk;
        }

        $educator->save();

        return $this->sendResponse(Constant::$SUCCESS,  ['educator_id' => $educator->id]);
    }

    public function updateEducator(Request $request)
    {
        $educator = Educator::find($request->input('educator_id'));
        $educator->first_name = $request->input("first_name");
        $educator->last_name = $request->input("last_name");
        $educator->bio = $request->input("bio");
        $educator->save();

        // Check for file state
        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        // Update related File
        $uc = new UploadController();
        $uk = $request->input('upload_key');
        $result = $uc->updateFileState($file_state, tenant()->id, 1, $educator, "image", $uk);

        return $this->sendResponse($result, null);
    }

    public function editEducatorImage(Request $request)
    {
        $educator = Educator::find($request->input('educator_id'));
        $action = $request->input('action');
        $file = $request->file('file');

        if ($action != Constant::$FILE_ACTION_DELETE) {
            $size = $file->getSize() / 1024;
            if ($size > Constant::$LOGO_SIZE_LIMIT)
                return $this->sendResponse(
                    Constant::$FILE_SIZE_LIMIT_EXCEEDED,
                    ['limit' => Constant::$LOGO_SIZE_NAME_LIMIT . "kb"]
                );
        }

        Helper::uploadFileToDisk(
            $action,
            $educator,
            'image',
            'public',
            'images/educators',
            '.png',
            $file
        );

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteEducator(Request $request)
    {
        $educator = Educator::find($request->input('educator_id'));
        $educator->courses()->detach();
        $educator->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function fetchEducators(Request $request)
    {
        $educators = Educator::all()->map(function ($educator) {
            return [
                "id" => $educator->id,
                "first_name" => $educator->first_name,
                "last_name" => $educator->last_name,
                "image" => $educator->image,
                "bio" => $educator->bio,
            ];
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $educators);
    }

    public function getImage(Request $request, $educator_id)
    {
        $educator = Educator::find($educator_id);
        if ($educator && $educator->image) {
            $path = storage_path("app\public\\" . $educator->image);
            $path = str_replace('/', '\\', $path);
            $headers = array(
                'Content-Type' => 'image/png',
            );

            return response()->file($path, $headers);
        } else return null;
    }
}
