<?php


namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Popup;
use Illuminate\Http\Request;


class PopupsController extends BaseController
{
    public function createPopup(Request $request){
        $popup = new Popup();
        $popup->title = $request->input("title");
        $popup->text = $request->input("text");
        $popup->link = $request->input("link");
        $popup->link_title = $request->input("link_title");
        $popup->submit_text = $request->input("submit_text");
        $popup->has_email_input = $request->input("has_email_input");
        $popup->has_name_input = $request->input("has_name_input");
        $popup->has_phone_input = $request->input("has_phone_input");
        $popup->has_city_input = $request->input("has_city_input");
        $popup->has_province_input = $request->input("has_province_input");
        $popup->active = $request->input("active");
        $popup->save();

        return $this->sendResponse(Constant::$SUCCESS,  ['popup_id' => $popup->id]);
    }

    public function updatePopup(Request $request){
        $popup = Popup::find($request->input('popup_id'));

        $popup->title = $request->input("title");
        $popup->text = $request->input("text");
        $popup->link = $request->input("link");
        $popup->link_title = $request->input("link_title");
        $popup->submit_text = $request->input("submit_text");
        $popup->has_email_input = $request->input("has_email_input");
        $popup->has_name_input = $request->input("has_name_input");
        $popup->has_phone_input = $request->input("has_phone_input");
        $popup->has_city_input = $request->input("has_city_input");
        $popup->has_province_input = $request->input("has_province_input");
        $popup->active = $request->input("active");
        $popup->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deletePopup(Request $request){
        $popup = Popup::find($request->input('popup_id'));
        $popup ->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function fetchPopups(Request $request){
        $popups = Popup::all()->map(function ($popup){
            return [
                "id" => $popup->id,
                "title" => $popup->title,
                "link" => $popup->link,
                "text" => $popup->text,
                "link_title" => $popup->link_title,
                "submit_text" => $popup->submit_text,
                "has_email_input" => $popup->has_email_input,
                "has_name_input" => $popup->has_name_input,
                "has_phone_input" => $popup->has_phone_input,
                "has_city_input" => $popup->has_city_input,
                "has_province_input" => $popup->has_province_input,
                "active" => $popup->active,
            ];
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $popups);
    }
}
