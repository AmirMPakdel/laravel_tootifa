<?php


namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\ContentImage;
use App\Models\ContentVideo;
use App\Models\LandingPage;
use Illuminate\Http\Request;


class LandingPageController extends BaseController
{
    public function createLandingPage(Request $request){
        $landingPage = new LandingPage();
        $landingPage->title = $request->input("title");
        $landingPage->text = $request->input("text");
        $landingPage->link = $request->input("link");
        $landingPage->link_title = $request->input("link_title");
        $landingPage->submit_text = $request->input("submit_text");
        $landingPage->has_email_input = $request->input("has_email_input");
        $landingPage->has_name_input = $request->input("has_name_input");
        $landingPage->has_phone_input = $request->input("has_phone_input");
        $landingPage->has_city_input = $request->input("has_city_input");
        $landingPage->has_province_input = $request->input("has_province_input");
        $landingPage->active = $request->input("active");

        if($request->input('image_url')){
            $content_image = new ContentImage();
            $content_image->url = $request->input('image_url');
            $content_image->size = $request->input('image_size');
            $content_image->belongs_to = Constant::$BELONGING_LP;
            $landingPage->content_image()->save($content_image);
        }

        if($request->input('video_url')){
            $content_video = new ContentVideo();
            $content_video->url = $request->input('video_url');
            $content_video->size = $request->input('video_size');
            $content_video->belongs_to = Constant::$BELONGING_LP;
            $landingPage->content_video()->save($content_video);
        }


        $landingPage->save();

        return $this->sendResponse(Constant::$SUCCESS,  ['landing_page_id' => $landingPage->id]);
    }

    public function updatelandingPage(Request $request){
        $landingPage = LandingPage::find($request->input('landingPage_id'));

        $landingPage->title = $request->input("title");
        $landingPage->text = $request->input("text");
        $landingPage->link = $request->input("link");
        $landingPage->link_title = $request->input("link_title");
        $landingPage->submit_text = $request->input("submit_text");
        $landingPage->has_email_input = $request->input("has_email_input");
        $landingPage->has_name_input = $request->input("has_name_input");
        $landingPage->has_phone_input = $request->input("has_phone_input");
        $landingPage->has_city_input = $request->input("has_city_input");
        $landingPage->has_province_input = $request->input("has_province_input");
        $landingPage->active = $request->input("active");
        $landingPage->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deletelandingPage(Request $request){
        $landingPage = LandingPage::find($request->input('landing_page_id'));
        $landingPage ->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function fetchlandingPages(Request $request){
        $landingPages = LandingPage::all()->map(function ($landingPage){
            return [
                "id" => $landingPage->id,
                "title" => $landingPage->title,
                "link" => $landingPage->link,
                "text" => $landingPage->text,
                "link_title" => $landingPage->link_title,
                "submit_text" => $landingPage->submit_text,
                "has_email_input" => $landingPage->has_email_input,
                "has_name_input" => $landingPage->has_name_input,
                "has_phone_input" => $landingPage->has_phone_input,
                "has_city_input" => $landingPage->has_city_input,
                "has_province_input" => $landingPage->has_province_input,
                "active" => $landingPage->active,
            ];
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $landingPages);
    }
}
