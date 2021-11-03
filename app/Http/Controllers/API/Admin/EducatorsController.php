<?php


namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\UploadManager;
use App\Models\Educator;
use Illuminate\Http\Request;


class EducatorsController extends BaseController
{
    public function createEducator(Request $request)
    {
        $educator = new Educator();
        $educator->first_name = $request->input("first_name");
        $educator->last_name = $request->input("last_name");
        $educator->bio = $request->input("bio");

        $result = UploadManager::saveFile(tenant()->id, 1, $educator, 'image', false, $request->input('upload_key'));
        if ($result == Constant::$SUCCESS) $educator->save();

        return $this->sendResponse($result,  ['educator_id' => $educator->id]);
    }

    public function updateEducator(Request $request)
    {
        $educator = Educator::find($request->input('educator_id'));
        $educator->first_name = $request->input("first_name");
        $educator->last_name = $request->input("last_name");
        $educator->bio = $request->input("bio");

        // Check for file state
        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        // Update related File
        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $educator,
            'image',
            false,
            $request->input('upload_key')
        );
        if ($result == Constant::$SUCCESS) $educator->save();

        return $this->sendResponse($result, null);
    }

    public function deleteEducator(Request $request)
    {
        $educator = Educator::find($request->input('educator_id'));

        $result = UploadManager::deleteFile(tenant()->id, $educator->image);
        if ($result == Constant::$SUCCESS){
            $educator->courses()->detach();
            $educator->delete();
        }
        
        return $this->sendResponse($result, null);
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
}
