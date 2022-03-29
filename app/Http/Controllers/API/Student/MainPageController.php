<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\Course;
use App\Models\LevelOneGroup;
use App\Models\LevelThreeGroup;
use App\Models\LevelTwoGroup;
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
        $contents = MainContent::visible()->get()->map(function ($content){
            $c = [
                'id' => $content->id,
                'title' => $content->title,
                'has_link' => $content->has_link,
                'link' => $content->link,
                'link_title' => $content->link_title,
                'text' => $content->text,
                'visible' => $content->visible,
                'type' => $content->type,
            ];

            switch ($content->type){
                case Constant::$CONTENT_TYPE_VIDEO:
                    $c['url'] = $content->content_video->url;
                    $c['size'] = $content->content_video->size;
                    break;
                case Constant::$CONTENT_TYPE_IMAGE:
                    $c['url'] = $content->content_image->url;
                    $c['size'] = $content->content_image->size;
                    break;
                case Constant::$CONTENT_TYPE_NONE:
                    $c['url'] = "/";
                    $c['size'] = 0;
                    break;
                // case Constant::$CONTENT_TYPE_VOICE:
                //     $c['url'] = $content->content_voice->url;
                //     $c['size'] = $content->content_voice->size;
                //     break;
                // case Constant::$CONTENT_TYPE_TEXT:
                //     $c['text'] = $content->content_text->text;
                //     break;
                // case Constant::$CONTENT_TYPE_SLIDER:
                //     $c['slides'] = $content->content_slider->content_images()->get()->map(function ($image){
                //         return ["url" => $image->url, "size" => $image->size];
                //     });
            }

            return $c;
        });

        $course_lists = MainCourseList::visible()->get()->map(function ($course_list){
            return [
                'title' => $course_list->title,
                'default_type' => $course_list->default_type,
                // 'list' => $course_list->list,
                'g1' => $course_list->level_one_group_id,
                'g2' => $course_list->level_two_group_id,
                'g3' => $course_list->level_three_group_id,
            ];
        });

        $post_lists = MainPostList::visible()->get()->map(function ($post_list){
            return [
                'title' => $post_list->title,
                'default_type' => $post_list->default_type,
                // 'list' => $post_list->list,
            ];
        });

        // todo only visibles
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
            'banner_cover' => $properties->banner_cover,
            'page_cover' => $properties->page_cover,
            'page_cover_title' => $properties->page_cover_title,
            'page_cover_text' => $properties->page_cover_text,
            'page_cover_has_link' => $properties->page_cover_has_link,
            'page_cover_link' => $properties->page_cover_link,
            'page_cover_link_title' => $properties->page_cover_link_title,
            'page_cover_template' => $properties->page_cover_template,
            'page_logo' => $properties->page_logo,
            'store_open' => $properties->store_open,
            'banner_link' => $properties->banner_link,
            'banner_text' => $properties->banner_text,
            'page_title' => $properties->page_title,
            'content_hierarchy' => $properties->content_hierarchy,
            'footer_links' => $properties->footer_links,
            'footer_telephones' => $properties->footer_telephones,
            'footer_app_links' => Constant::$APP_LINKS,
            'contents' => $contents,
            'course_lists' => $course_lists,
            // 'post_lists' => $post_lists,
            // 'main_forms' => $main_forms,
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

    public function getMainCourseListData(Request $request){
        $default_type = MainCourseList::find($request->input('course_list_id'));
        $group = $request->input('group');

        if(!$default_type){
            return $this->sendResponse(Constant::$NO_DEFAULT_TYPE, null);
        }

        // which order
        switch ($default_type) {
            case Constant::$MAIN_LIST_DEFAULT_TYPE_HIGHEST_SCORE:
                $order_by = "score";
                $order_direction = "desc";
                break;
            case Constant::$MAIN_LIST_DEFAULT_TYPE_LAST_CREATED:
                $order_by = "created_at";
                $order_direction = "asc";
                break;
            case Constant::$MAIN_LIST_DEFAULT_TYPE_MOST_SELLS:
                $order_by = "sells";
                $order_direction = "desc";
                break;
            case Constant::$MAIN_LIST_DEFAULT_TYPE_MOST_VISITED:
                $order_by = "visits_count";
                $order_direction = "asc";
                break;
            default:
                $order_by = "created_at";
                $order_direction = "desc";
        }

        // which group
        if ($group && isset($group['level'])) {
            switch ($group['level']) {
                case 1:
                    $group = LevelOneGroup::find($group['id']);
                    break;
                case 2:
                    $group = LevelTwoGroup::find($group['id']);
                    break;
                case 3:
                    $group = LevelThreeGroup::find($group['id']);
                    break;
                default:
                    $group = null;
            }
        } else $group = null;


        if ($group) {
            $courses = $group->courses()->where([
                ['validation_status', 1],
            ])->orderBy($order_by, $order_direction)
                ->limit(11)->map(function ($course) {
                    return $this->buildListCourseObject($course);
            });
        } else {
            $courses = Course::where([
                ['validation_status', 1],
            ])->orderBy($order_by, $order_direction)
                ->limit(11)->map(function ($course) {
                    return $this->buildListCourseObject($course);
            });
        }

        return $this->sendResponse(Constant::$SUCCESS, $courses);
    }
        
}
