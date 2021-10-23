<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\MainContent;
use App\Models\MainCourseList;
use App\Models\MainForm;
use App\Models\MainPageProperties;
use App\Models\MainPostList;
use App\Models\Popup;
use Illuminate\Http\Request;


class MainPageController extends BaseController
{
    
    public function loadMainPage(Request $request){
        // set visit 
        Helper::setMainVisit();

        $properties = MainPageProperties::all()[0];
        $contents = MainContent::all()->map(function ($content){
            $c = [
                'id' => $content->id,
                'title' => $content->title,
                'link' => $content->link,
                'type' => $content->type,
            ];

            switch ($content->type){
                case Constant::$CONTENT_TYPE_VIDEO:
                    $c['url'] = $content->content_video->url;
                    $c['size'] = $content->content_video->size;
                    break;
                case Constant::$CONTENT_TYPE_VOICE:
                    $c['url'] = $content->content_voice->url;
                    $c['size'] = $content->content_voice->size;
                    break;
                case Constant::$CONTENT_TYPE_IMAGE:
                    $c['url'] = $content->content_image->url;
                    $c['size'] = $content->content_image->size;
                    break;
                case Constant::$CONTENT_TYPE_TEXT:
                    $c['text'] = $content->content_text->text;
                    break;
                case Constant::$CONTENT_TYPE_SLIDER:
                    $c['slides'] = $content->content_slider->content_images()->get()->map(function ($image){
                        return ["url" => $image->url, "size" => $image->size];
                    });
            }

            return $c;
        });

        $course_lists = MainCourseList::all()->map(function ($course_list){
            return [
                'title' => $course_list->title,
                'default_type' => $course_list->default_type,
                'list' => $course_list->list,
            ];
        });

        $post_lists = MainPostList::all()->map(function ($post_list){
            return [
                'title' => $post_list->title,
                'default_type' => $post_list->default_type,
                'list' => $post_list->list,
            ];
        });

        $main_forms = MainForm::all()->map(function ($main_form){
            return [
                'title' => $main_form->title,
                'text' => $main_form->text,
                'submit_text' => $main_form->submit_text,
                'has_email_input' => $main_form->has_email_input,
                'has_name_input' => $main_form->has_name_input,
                'has_phone_input' => $main_form->has_phone_input,
                'has_city_input' => $main_form->has_city_input,
                'has_province_input' => $main_form->has_province_input
            ];
        });


        return [
            'is_banner_on' => $properties->is_banner_on,
            'store_open' => $properties->store_open,
            'blog_open' => $properties->blog_open,
            'banner_link' => $properties->banner_link,
            'banner_text' => $properties->banner_text,
            'page_title' => $properties->page_title,
            'content_hierarchy' => $properties->content_hierarchy,
            'footer_links' => $properties->footer_links,
            'contents' => $contents,
            'course_lists' => $course_lists,
            'post_lists' => $post_lists,
            'main_forms' => $main_forms,
        ];
    }

    public function loadActivePopups(Request $request){
        $popups = Popup::where('active',1)->map(function ($popup){
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
            ];
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $popups);
    }
        
}
