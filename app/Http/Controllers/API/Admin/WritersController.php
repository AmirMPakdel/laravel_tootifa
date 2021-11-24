<?php


namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Includes\UploadManager;
use App\Models\Writer;
use Illuminate\Http\Request;


class WritersController extends BaseController
{
    public function createWriter(Request $request)
    {
        $writer = new Writer();
        $writer->first_name = $request->input("first_name");
        $writer->last_name = $request->input("last_name");
        $writer->bio = $request->input("bio");

        $result = UploadManager::saveFile(tenant()->id, 1, $writer, 'image', false, false, $request->input('upload_key'));
        if ($result == Constant::$SUCCESS) $writer->save();

        return $this->sendResponse($result, ['writer_id' => $writer->id]);
    }

    public function updateWriter(Request $request)
    {
        $writer = Writer::find($request->input('writer_id'));
        $writer->first_name = $request->input("first_name");
        $writer->last_name = $request->input("last_name");
        $writer->bio = $request->input("bio");

        // Check for file state
        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        // Update related File
        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $writer,
            "image",
            false,
            false,
            $request->input('upload_key')
        );
        if ($result == Constant::$SUCCESS) $writer->save();

        return $this->sendResponse($result, null);
    }

    public function deleteWriter(Request $request)
    {
        $writer = Writer::find($request->input('writer_id'));

        $result = UploadManager::deleteFile(tenant()->id, $writer->image);
        if ($result == Constant::$SUCCESS){
            $writer->courses()->detach();
            $writer->delete();
        }
        
        return $this->sendResponse($result, null);
    }

    public function fetchWriters(Request $request)
    {
        $writers = Writer::all()->map(function ($writer) {
            return [
                "id" => $writer->id,
                "first_name" => $writer->first_name,
                "last_name" => $writer->last_name,
                "image" => $writer->image,
                "bio" => $writer->bio,
            ];
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $writers);
    }
}
