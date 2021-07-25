<?php


namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Comment;
use App\Models\Course;
use Illuminate\Http\Request;


class CommentsController extends BaseController
{
    public function fetchCourseCheckedComments(Request $request, $chunk_count, $page_count){
        // TODO test
        $valid = $request->input("valid");
        $course = Course::find($request->input("course_id"));

        $comments = $course->comments->where([
            ['valid', $valid],
            ['checked', 1],
        ])->get()->map(function ($comment){
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'score' => $comment->score,
                'student_first_name' => $comment->student->first_name,
                'student_last_name' => $comment->student->last_name
            ];
        });

        $last_items = (collect($comments)->sortByDesc('id')->chunk($chunk_count))[$page_count];
        return $this->sendResponse(Constant::$SUCCESS, $last_items);
    }

    public function fetchCourseUnCheckedComments(Request $request, $chunk_count, $page_count){
        // TODO test
        $course = Course::find($request->input("course_id"));

        $comments = $course->comments()->where([
            ['checked', 0],
        ])->get()->map(function ($comment){
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'score' => $comment->score,
                'student_first_name' => $comment->student->first_name,
                'student_last_name' => $comment->student->last_name
            ];
        });

        $last_items = (collect($comments)->sortByDesc('id')->chunk($chunk_count))[$page_count];
        return $this->sendResponse(Constant::$SUCCESS, $last_items);
    }

    public function getCourseUnCheckedCommentsCount(Request $request){
        // TODO test
        $course = Course::find($request->input("course_id"));
        $count = $course->comments->where('checked', 0)->count();
        return $this->sendResponse(Constant::$SUCCESS, $count);
    }

    public function deleteComment(Request $request){
        // TODO test
        $comment = Comment::find($request->input('comment_id'));
        $comment->delete();
        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function setCommentValid(Request $request){
        // TODO test
        $comment = Comment::find($request->input('comment_id'));
        $comment->valid = $request->input($request->input('valid'));
        $comment->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function setCommentChecked(Request $request){
        // TODO test
        $comment = Comment::find($request->input('comment_id'));
        $comment->checked = 1;
        $comment->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }
}
