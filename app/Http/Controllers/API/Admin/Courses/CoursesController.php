<?php


namespace App\Http\Controllers\API\Admin\Courses;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\Admin\GroupsController;
use App\Http\Controllers\API\Admin\TagsController;
use App\Includes\Constant;
use App\Models\Category;
use App\Models\Course;
use App\Models\LevelOneGroup;
use App\Models\LevelThreeGroup;
use App\Models\LevelTwoGroup;
use App\Models\Tag;
use Illuminate\Http\Request;

class CoursesController extends BaseController
{
    public function createCourse(Request $request){
        // fetching data
        $title = $request->input('title');
        $price = $request->input('price');
        $groups = (object)$request->input('groups');
        $tags = (array)$request->input('tags');
        $category = Category::find($request->input('category_id'));

        // check title
        if(Course::where('title', $title)->exists())
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        // check groups hierarchy
        if(!GroupsController::checkGroupsHierarchy($groups))
            return $this->sendResponse(Constant::$INVALID_GROUP_HIERARCHY, null);

        // create course
        $course = new Course();
        $course->title = $title;
        $course->price = $price;

        // add it to tags
        foreach (Tag::find($tags) as $tag)
            $tag->courses()->save($course);

        // add it to groups
        $g1 = LevelOneGroup::find($groups->g1);
        $g2 = LevelTwoGroup::find($groups->g2);
        $g3 = LevelThreeGroup::find($groups->g3);

        if ($g1) $g1->courses()->save($course);
        if ($g2) $g2->courses()->save($course);
        if ($g3) $g3->courses()->save($course);

        // add it to category
        $category->courses()->save($course);

        return $this->sendResponse(Constant::$SUCCESS,  ['course_id' => $course->id]);
    }


    public function fetchCourses(Request $request){
        $filters = (object)$request->input('filters');
        $sorting_mode = $request->input('sorting_mode');

        $search_phrase = $filters->search_phrase;
        $group = (object)$filters->group;

        // which group
        if(isset($group->level)) {
            switch ($group->level) {
                case 1:
                    $group = LevelOneGroup::find($group->id);
                    break;
                case 2:
                    $group = LevelTwoGroup::find($group->id);
                    break;
                case 3:
                    $group = LevelThreeGroup::find($group->id);
                    break;
                default:
                    $group = null;
            }
        } else $group = null;


        // which order
        switch ($sorting_mode){
            case Constant::$SM_HIGHEST_PRICE:
                $order_by = "price";
                $order_direction = "desc";
                break;
            case Constant::$SM_LOWEST_PRICE:
                $order_by = "price";
                $order_direction = "asc";
                break;
            case Constant::$SM_MOST_SELLS:
                $order_by = "sells";
                $order_direction = "desc";
                break;
            case Constant::$SM_LEAST_SELLS:
                $order_by = "sells";
                $order_direction = "asc";
                break;
            case Constant::$SM_MOST_VISITS:
                $order_by = "visits_count";
                $order_direction = "desc";
                break;
            case Constant::$SM_LEAST_VISITS:
                $order_by = "visits_count";
                $order_direction = "asc";
                break;
            case Constant::$SM_NEWEST:
                $order_by = "created_at";
                $order_direction = "desc";
                break;
            case Constant::$SM_OLDEST:
                $order_by = "created_at";
                $order_direction = "asc";
                break;
            default:
                $order_by = "created_at";
                $order_direction = "desc";
        }

        $query = [];
        if($search_phrase)
            array_push($query, ['title', 'like', "%{$search_phrase}%"]);

        if($group)
            $courses = $group->courses()->where($query)
                ->orderBy($order_by, $order_direction)
                ->get()->map(function ($course) {
                    return $this->buildListCourseObject($course);
                })->toArray();
        else
            $courses = Course::where($query)->orderBy($order_by, $order_direction)
                ->get()->map(function ($course) {
                    return $this->buildListCourseObject($course);
                })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $courses);
    }

    public function fetchSpecificCourses(Request $request){
        $ids = (array)$request->input('ids');

        $courses = Course::find($ids)->map(function ($course) {
            return $this->buildListCourseObject($course);
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $courses);
    }

    public function loadCourse(Request $request){

        $course = Course::where('id',$request->input('course_id'))->get()->map(function ($course) {
            return $this->buildCourseObject($course);
        })->toArray()[0];

        return $this->sendResponse(Constant::$SUCCESS, $course);
    }

    public function getLogo(Request $request, $course_id){
        $course = Course::find($course_id);
        if($course && $course->logo){
            $path = storage_path( "app\public\\" . $course->logo);
            $path = str_replace('/', '\\', $path);
            $headers = array(
                'Content-Type' => 'image/png',
            );

            return response()->file($path, $headers);
        }else return null;
    }

    public function getCover(Request $request, $course_id){
        $course = Course::find($course_id);
        if($course && $course->cover){
            $path = storage_path( "app\public\\" . $course->cover);
            $path = str_replace('/', '\\', $path);
            $headers = array(
                'Content-Type' => 'image/png',
            );

            return response()->file($path, $headers);
        }else return null;
    }


    private function buildListCourseObject($course){
        return [
            'id' => $course->id,
            'title' => $course->title,
            'price' => $course->price,
            'sells' => $course->sells,
            'score' => $course->score,
            'visits_count' => $course->visits_count,
            'g1' => $course->level_one_group_id,
            'g2' => $course->level_two_group_id,
            'g3' => $course->level_three_group_id,
        ];
    }

    private function buildCourseObject($course){
        $tags = $course->tags()->get()->map(function ($tag){
            return ['id' => $tag->id, 'title' => $tag->title];
        });

        $educators = $course->educators()->get()->map(function ($educator){
            return [
                'id' => $educator->id,
                'first_name' => $educator->first_name,
                'last_name' => $educator->last_name,
                'bio' => $educator->bio
            ];
        });

        $headings = $course->course_headings()->get()->map(function ($heading){
            return ['id' => $heading->id, 'title' => $heading->title];
        });

        $intro_video = ($course->course_introduction) ? [
            'id' => $course->course_introduction->id,
            'url' => $course->course_introduction->content_video->url,
            'size' => $course->course_introduction->content_video->size
        ] : null;

        $contents = $course->course_contents()->get()->map(function ($content){
            $c = [
                'id' => $content->id,
                'title' => $content->title,
                'type' => $content->type,
                'is_free' => $content->is_free
            ];

            switch ($content->type){
                case Constant::$CONTENT_TYPE_VIDEO:
                    $c['url'] = $content->content_video->url;
                    $c['size'] = $content->content_video->size;
                    $c['encoding'] = $content->content_video->encoding;
                    break;
                case Constant::$CONTENT_TYPE_VOICE:
                    $c['url'] = $content->content_voice->url;
                    $c['size'] = $content->content_voice->size;
                    break;
                case Constant::$CONTENT_TYPE_DOCUMENT:
                    $c['url'] = $content->content_document->url;
                    $c['size'] = $content->content_document->size;
            }

            return $c;
        });

        return [
            'id' => $course->id,
            'title' => $course->title,
            'price' => $course->price,
            'sells' => $course->sells,
            'score' => $course->score,
            'visits_count' => $course->visits_count,
            'g1' => $course->level_one_group_id,
            'g2' => $course->level_two_group_id,
            'g3' => $course->level_three_group_id,
            "tags" => $tags,
            "duration" => $course->duration,
            "has_discount" => $course->has_discount,
            "discount" => $course->discount,
            "holding_status" => $course->holding_status,
            "validation_status" => $course->validation_status,
            "validation_status_message" => $course->validation_status_message,
            "release_date" => $course->release_date,
            "subjects" => $course->subjects,
            "short_desc" => $course->short_desc,
            "long_desc" => $course->long_desc,
            "requirements" => $course->requirements,
            "suggested_courses" => $course->suggested_courses,
            "suggested_posts" => $course->suggested_posts,
            "intro_video" => $intro_video,
            "content_hierarchy" => $course->content_hierarchy,
            "headings" => $headings,
            "contents" => $contents,
            "educators" => $educators
        ];
    }


}
