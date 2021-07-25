<?php


namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\Writer;
use Illuminate\Http\Request;


class WritersController extends BaseController
{
    public function createWriter(Request $request){
        $writer = new Writer();
        $writer->first_name = $request->input("first_name");
        $writer->last_name = $request->input("last_name");
        $writer->bio = $request->input("bio");
        $writer->save();

        if($request->exists('image')){
            $image = $request->file('image');

            $size = $image->getSize() / 1024;
            if ($size > Constant::$LOGO_SIZE_LIMIT)
                return $this->sendResponse(
                    Constant::$FILE_SIZE_LIMIT_EXCEEDED,
                    ['limit'=>Constant::$LOGO_SIZE_NAME_LIMIT."kb"]
                );

            Helper::uploadFileToDisk(
                Constant::$FILE_ACTION_CREATE,
                $writer,
                'image',
                'public',
                'images/writers',
                '.png',
                $image
            );
        }

        return $this->sendResponse(Constant::$SUCCESS,  ['writer_id' => $writer->id]);
    }

    public function updateWriter(Request $request){
        $writer = Writer::find($request->input('writer_id'));
        $writer->first_name = $request->input("first_name");
        $writer->last_name = $request->input("last_name");
        $writer->bio = $request->input("bio");
        $writer->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editWriterImage(Request $request)
    {
        $writer = Writer::find($request->input('writer_id'));
        $action = $request->input('action');
        $file = $request->file('file');

        if($action != Constant::$FILE_ACTION_DELETE){
            $size = $file->getSize() / 1024;
            if ($size > Constant::$LOGO_SIZE_LIMIT)
                return $this->sendResponse(
                    Constant::$FILE_SIZE_LIMIT_EXCEEDED,
                    ['limit'=>Constant::$LOGO_SIZE_NAME_LIMIT."kb"]
                );
        }

        Helper::uploadFileToDisk(
            $action,
            $writer,
            'image',
            'public',
            'images/writers',
            '.png',
            $file
        );

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteWriter(Request $request){
        $writer = Writer::find($request->input('writer_id'));
        $writer->courses()->detach();
        $writer->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function fetchWriters(Request $request){
        $writers = Writer::all()->map(function ($writer){
            return [
                "id" => $writer->id,
                "first_name" => $writer->first_name,
                "last_name" => $writer->last_name,
                "bio" => $writer->bio,
            ];
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $writers);
    }

    public function getImage(Request $request, $writer_id){
        $writer = Writer::find($writer_id);
        if($writer && $writer->image){
            $path = storage_path( "app\public\\" . $writer->image);
            $path = str_replace('/', '\\', $path);
            $headers = array(
                'Content-Type' => 'image/png',
            );

            return response()->file($path, $headers);
        }else return null;
    }
}
