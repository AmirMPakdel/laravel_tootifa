<?php


namespace App\Http\Controllers\API\Admin\MainPage;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\ContentImage;
use App\Models\ContentSlider;
use App\Models\ContentText;
use App\Models\ContentVideo;
use App\Models\ContentVoice;
use App\Models\Course;
use App\Models\MainContent;
use App\Models\MainCourseList;
use App\Models\MainPageProperties;
use App\Models\MainPostList;
use Illuminate\Http\Request;


class UserMainPageController extends BaseController
{
    public function getPageLogo(Request $request){
        $properties = MainPageProperties::all()[0];

        if($properties->page_logo){
            $path = storage_path( "app\public\\" . $properties->page_logo);
            $path = str_replace('/', '\\', $path);
            $headers = array(
                'Content-Type' => 'image/png',
            );

            return response()->file($path, $headers);
        }else return null;
    }

    public function getPageCover(Request $request){
        $properties = MainPageProperties::all()[0];

        if($properties->page_cover){
            $path = storage_path( "app\public\\" . $properties->page_cover);
            $path = str_replace('/', '\\', $path);
            $headers = array(
                'Content-Type' => 'image/png',
            );

            return response()->file($path, $headers);
        }else return null;
    }

    public function getBannerCover(Request $request){
        $properties = MainPageProperties::all()[0];

        if($properties->banner_cover){
            $path = storage_path( "app\public\\" . $properties->banner_cover);
            $path = str_replace('/', '\\', $path);
            $headers = array(
                'Content-Type' => 'image/png',
            );

            return response()->file($path, $headers);
        }else return null;
    }

    public function loadMainPage(Request $request){
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
        ];
    }
}
