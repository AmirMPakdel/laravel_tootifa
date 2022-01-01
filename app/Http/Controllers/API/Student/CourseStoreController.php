<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\Course;
use App\Models\LevelOneGroup;
use App\Models\LevelTwoGroup;
use App\Models\LevelThreeGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class CourseStoreController extends BaseController
{
    public function fetchCourses(Request $request, $chunk_count, $page_count)
    {
        $filters = $request->input('filters');
        $sorting_mode = $request->input('sorting_mode');

        $search_phrase = $filters['search_phrase'];
        $group = $filters['group'];

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


        // which order
        switch ($sorting_mode) {
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
        if ($search_phrase)
            array_push($query, ['title', 'like', "%{$search_phrase}%"]);

        if ($group) {
            $paginator = $group->courses()->where($query)->orderBy($order_by, $order_direction)
                ->paginate($chunk_count, ['*'], 'page', $page_count);
        } else {
            $paginator = Course::where($query)->orderBy($order_by, $order_direction)
                ->paginate($chunk_count, ['*'], 'page', $page_count);
        }

        $courses = $paginator->map(function ($course) {
            return $this->buildListCourseObject($course);
        });

        if (sizeof($courses) == 0) return $this->sendResponse(Constant::$NO_DATA, null);
        $result = ["total_size" => $paginator->total(), "list" => $courses];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function loadCourse(Request $request)
    {
        // set visit 
        Helper::setCourseVisit($request->input('course_id'));

        $course = Course::where('id', $request->input('course_id'))->get()->map(function ($course) {
            return $this->buildCourseObject($course, null);
        })->toArray()[0];

        return $this->sendResponse(Constant::$SUCCESS, $course);
    }

    public function loadCourseForLoggedIn(Request $request)
    {
        // set visit 
        Helper::setCourseVisit($request->input('course_id'));

        $student = $request->input('student');
        $course = Course::where('id', $request->input('course_id'))->get()->map(function ($course) use ($student) {
            return $this->buildCourseObject($course, $student);
        })->toArray()[0];

        return $this->sendResponse(Constant::$SUCCESS, $course);
    }

    private function buildCourseObject($course, $student)
    {
        $registered = false;
        $has_access = false;

        if ($student) {
            $registered = DB::table('course_student')
                ->whereCourseId($course->id)
                ->whereStudentId($student->id)
                ->count() > 0;

            $has_access = DB::table('course_student')
                ->whereCourseId($course->id)
                ->whereStudentId($student->id)
                ->whereAccess(1)
                ->count() > 0;
        }

        $tags = $course->tags()->get()->map(function ($tag) {
            return ['id' => $tag->id, 'title' => $tag->title];
        });

        $educators = $course->educators()->get()->map(function ($educator) {
            return [
                'id' => $educator->id,
                'first_name' => $educator->first_name,
                'last_name' => $educator->last_name,
                'bio' => $educator->bio
            ];
        });

        $headings = $course->course_headings()->get()->map(function ($heading) {
            return ['id' => $heading->id, 'title' => $heading->title];
        });

        $intro_video = ($course->course_introduction) ? [
            'id' => $course->course_introduction->id,
            'url' => $course->course_introduction->content_video->url,
            'size' => $course->course_introduction->content_video->size
        ] : null;

        $contents = $course->course_contents()->get()->map(function ($content) use ($has_access) {
            $c = [
                'id' => $content->id,
                'title' => $content->title,
                'type' => $content->type,
                'is_free' => $content->is_free,
            ];

            switch ($content->type) {
                case Constant::$CONTENT_TYPE_VIDEO:
                    $c['url'] = ($has_access || $content->is_free) ? $content->content_video->url : null;
                    $c['size'] = $content->content_video->size;
                    $c['encoding'] = $content->content_video->encoding;
                    break;
                case Constant::$CONTENT_TYPE_VOICE:
                    $c['url'] = ($has_access || $content->is_free) ? $content->content_voice->url : null;
                    $c['size'] = $content->content_voice->size;
                    break;
                case Constant::$CONTENT_TYPE_DOCUMENT:
                    $c['url'] = ($has_access || $content->is_free) ? $content->content_document->url : null;
                    $c['size'] = $content->content_document->size;
            }

            return $c;
        });

        return [
            'registered' => $registered,
            'has_access' => $has_access,
            'title' => $course->title,
            'price' => $course->price,
            'sells' => $course->sells,
            'score' => $course->score,
            'visits_count' => $course->visits_count,
            'is_online' => $course->is_online,
            'g1' => $course->level_one_group_id,
            'g2' => $course->level_two_group_id,
            'g3' => $course->level_three_group_id,
            "tags" => $tags,
            "duration" => $course->duration,
            "has_discount" => $course->has_discount,
            "discount" => $course->discount,
            "holding_status" => $course->holding_status,
            "release_date" => $course->release_date,
            "subjects" => $course->subjects,
            "short_desc" => $course->short_desc,
            "long_desc" => $course->long_desc,
            "requirements" => $course->requirements,
            "suggested_courses" => $course->suggested_courses,
            "suggested_posts" => $course->suggested_posts,
            "intro_video" => $intro_video,
            "content_hierarchy" => $course->content_hierarchy,
            "is_comments_open" => $course->is_comments_open,
            "is_encrypted" => $course->is_encrypted,
            "headings" => $headings,
            "contents" => $contents,
            "educators" => $educators,
            "logo" => $course->logo,
            "cover" => $course->cover,
        ];
    }


    private function buildListCourseObject($course)
    {
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
}
