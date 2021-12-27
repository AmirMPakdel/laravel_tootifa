<?php


namespace App\Http\Controllers\API\Student;
use App\Http\Controllers\API\Admin\Courses\CoursesController;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Favorite;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class StudentCourseController extends BaseController
{
    // TODO Remove this (JUST FOR TEST)
    public function completeCourseRegistration(Request $request){
        $course = Course::find($request->input('course_id'));
        $this->registerInCourse($request->input('student'), $course);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function registerInCourse($student, $course){
        $cc = new CoursesController();
        $cc->addStudentToCourse($student, $course, Constant::$REGISTRATION_TYPE_WEBSITE);
    }

    public function fetchCourses(Request $request){
        $courses = $request->input('student')->courses->map(function ($course) {
            return $this->buildListCourseObject($course);
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $courses);
    }

    public function loadCourse(Request $request){
        $student = $request->input('student');
        $course = Course::where('id',$request->input('course_id'))->get()->map(function ($course) use ($student) {
            return $this->buildCourseObject($student, $course);
        })->toArray()[0];

        return $this->sendResponse(Constant::$SUCCESS, $course);
    }

    public function getCourseScore(Request $request){
        $score = Score::where([
            ['scorable_id' , $request->input('course_id')],
            ['scorable_type' , "App\Models\Course"],
            ['student_id' , $request->input('student')->id]
        ])->get();

        $s = 0;
        if (sizeof($score) > 0) $s = $score[0]->score;

        return $this->sendResponse(Constant::$SUCCESS, $s);
    }

    public function getCommentScore(Request $request){
        $score = Score::where([
            ['scorable_id' , $request->input('comment_id')],
            ['scorable_type' , "App\Models\Comment"],
            ['student_id' , $request->input('student')->id]
        ])->get();

        $s = 0;
        if (sizeof($score) > 0) $s = $score[0]->score;

        return $this->sendResponse(Constant::$SUCCESS, $s);
    }

    public function updateCourseScore(Request $request){
        $score = Score::where([
            ['scorable_id' , $request->input('course_id')],
            ['scorable_type' , "App\Models\Course"],
            ['student_id' , $request->input('student')->id]
        ])->get();

        $course = Course::find($request->input('course_id'));

        if (sizeof($score) > 0){
            $score = $score[0];
            $score->score = $request->input('score');
            $score->save();
        }else{
            $score = new Score();
            $score->student_id = $request->input('student')->id;
            $score->score = $request->input('score');
            $course->scores()->save($score);
        }

        // calculate course total score
        $course->score = Score::where([
            ['scorable_id' , $request->input('course_id')],
            ['scorable_type' , "App\Models\Course"],
        ])->avg('score');

        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function updateCommentScore(Request $request){
        $score = Score::where([
            ['scorable_id' , $request->input('comment_id')],
            ['scorable_type' , "App\Models\Comment"],
            ['student_id' , $request->input('student')->id]
        ])->get();

        $comment = Comment::find($request->input('comment_id'));

        if (sizeof($score) > 0){
            $score = $score[0];
            $score->score = $request->input('score');
            $score->save();
        }else{
            $score = new Score();
            $score->student_id = $request->input('student')->id;
            $score->score = $request->input('score');
            $comment->scores()->save($score);
        }

        // calculate course total score
        $comment->score = Score::where([
            ['scorable_id' , $request->input('comment_id')],
            ['scorable_type' , "App\Models\Comment"],
        ])->avg('score');
      
        $comment->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function fetchFavoriteCourses(Request $request){
        $courses = $request->input('student')->favorites()
            ->where("favoritable_type", "App\Models\Course")->get()
            ->map(function ($favorite){
                return $this->buildListCourseObject(Course::find($favorite->favoritable_id));
            });

        return $this->sendResponse(Constant::$SUCCESS, $courses);
    }

    public function addFavoriteCourse(Request $request){
        $exists = Favorite::where([
            ['favoritable_id' , $request->input('course_id')],
            ['favoritable_type' , "App\Models\Course"],
            ['student_id' , $request->input('student')->id]
        ])->exists();

        if (!$exists){
            $favorite = new Favorite();
            $favorite->student_id = $request->input('student')->id;

            $course = Course::find($request->input('course_id'));
            $course->favorites()->save($favorite);
        }

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function removeFavoriteCourse(Request $request){
        Favorite::where([
            ['favoritable_id' , $request->input('course_id')],
            ['favoritable_type' , "App\Models\Course"],
            ['student_id' , $request->input('student')->id]
        ])->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function fetchComments(Request $request, $chunk_count, $page_count){
        $course = Course::find($request->input("course_id"));
        $student = $request->input('student');

        $paginator = $course->comments()->where([
            ['valid', 1],
        ])->orderBy('id', "desc")->paginate($chunk_count, ['*'], 'page', $page_count);
        
        $comments = $paginator->map(function ($comment) use ($student){
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'score' => $comment->score,
                'student_first_name' => $comment->student->first_name,
                'student_last_name' => $comment->student->last_name,
                'is_owner' => $comment->student_id == $student->id
            ];
        });

        if (sizeof($comments) == 0) return $this->sendResponse(Constant::$NO_DATA, null);
        $result = ["total_size" => $paginator->total(), "list" => $comments];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function addComment(Request $request){
        $course = Course::find($request->input('course_id'));

        if (!$course->is_comments_open)
            return $this->sendResponse(Constant::$COMMENTS_NOT_OPEN, null);

        $comment = new Comment();
        $comment->student_id = $request->input('student')->id;
        $comment->content =  $request->input('content');
        $comment->valid = $course->all_comments_valid;
        $course->comments()->save($comment);

        return $this->sendResponse(Constant::$SUCCESS, ['comment_id' => $comment->id]);
    }

    public function removeComment(Request $request){
        $comment = Comment::find($request->input('comment_id'));
        $comment->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }


    private function buildListCourseObject($course){
        return [
            'id' => $course->id,
            'is_online' => $course->is_online,
            'title' => $course->title,
        ];
    }

    public function buildCourseObject($student, $course){
        $registered = DB::table('course_student')
                ->whereCourseId($course->id)
                ->whereStudentId($student->id)
                ->count() > 0;

        $has_access = DB::table('course_student')
                ->whereCourseId($course->id)
                ->whereStudentId($student->id)
                ->whereAccess(1)
                ->count() > 0;

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

        $contents = $course->course_contents()->get()->map(function ($content) use ($has_access){
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
            'id' => $course->id,
            'registered' => $registered,
            'has_access' => $has_access,
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
            "is_online" => $course->is_online,
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
            "cover" => $course->cover
        ];
    }

}

