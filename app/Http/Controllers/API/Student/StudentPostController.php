<?php


namespace App\Http\Controllers\API\Student;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Post;
use App\Models\Score;
use Illuminate\Http\Request;
use Exception;

class StudentPostController extends BaseController
{
    public function loadPost(Request $request){
        $post = Post::where('id',$request->input('post_id'))->get()->map(function ($post) {
            return $this->buildPostObject($post);
        })->toArray()[0];

        return $this->sendResponse(Constant::$SUCCESS, $post);
    }

    public function getPostScore(Request $request){
        $score = Score::where([
            ['scorable_id' , $request->input('post_id')],
            ['scorable_type' , "App\Models\Post"],
            ['student_id' , $request->input('student')->id]
        ])->get();

        $s = 0;
        if (sizeof($score) > 0) $s = $score[0]->score;

        return $this->sendResponse(Constant::$SUCCESS, $s);
    }

    public function updatePostScore(Request $request){
        $score = Score::where([
            ['scorable_id' , $request->input('post_id')],
            ['scorable_type' , "App\Models\Post"],
            ['student_id' , $request->input('student')->id]
        ])->get();

        $post = Post::find($request->input('post_id'));

        if (sizeof($score) > 0){
            $score = $score[0];
            $score->score = $request->input('score');
            $score->save();
        }else{
            $score = new Score();
            $score->student_id = $request->input('student')->id;
            $score->score = $request->input('score');
            $post->scores()->save($score);
        }

        // calculate post total score
        $scores = Score::where([
            ['scorable_id' , $request->input('post_id')],
            ['scorable_type' , "App\Models\Post"],
        ])->get();

        $sum = 0;
        foreach ($scores as $s) $sum += $s->score;
        $post->score = $sum / sizeof($scores);
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function fetchFavoritePosts(Request $request){
        $posts = $request->input('student')->favorites()
            ->where("favoritable_type", "App\Models\Post")->get()
            ->map(function ($favorite){
                return $this->buildListPostObject(Post::find($favorite->favoritable_id));
            });

        return $this->sendResponse(Constant::$SUCCESS, $posts);
    }

    public function addFavoritePost(Request $request){
        $exists = Favorite::where([
            ['favoritable_id' , $request->input('post_id')],
            ['favoritable_type' , "App\Models\Post"],
            ['student_id' , $request->input('student')->id]
        ])->exists();

        if (!$exists){
            $favorite = new Favorite();
            $favorite->student_id = $request->input('student')->id;

            $post = Post::find($request->input('post_id'));
            $post->favorites()->save($favorite);
        }

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function removeFavoritePost(Request $request){
        Favorite::where([
            ['favoritable_id' , $request->input('post_id')],
            ['favoritable_type' , "App\Models\Post"],
            ['student_id' , $request->input('student')->id]
        ])->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function fetchComments(Request $request, $chunk_count, $page_count){
        $post = Post::find($request->input("post_id"));
        $student = $request->input('student');

        $comments = $post->comments()->where([
            ['valid', 1],
        ])->get()->map(function ($comment) use ($student){
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'score' => $comment->score,
                'student_first_name' => $comment->student->first_name,
                'student_last_name' => $comment->student->last_name,
                'is_owner' => $comment->student_id == $student->id
            ];
        });

        try {
            $last_items = (collect($comments)->sortByDesc('id')->chunk($chunk_count))[$page_count];
            return $this->sendResponse(Constant::$SUCCESS, $last_items);
        }catch(Exception $e){
            return $this->sendResponse(Constant::$NO_DATA, null);
        }
    }

    public function addComment(Request $request){
        $post = Post::find($request->input('post_id'));

        if (!$post->is_comments_open)
            return $this->sendResponse(Constant::$COMMENTS_NOT_OPEN, null);

        $comment = new Comment();
        $comment->student_id = $request->input('student')->id;
        $comment->content =  $request->input('content');
        $comment->valid = $post->all_comments_valid;
        $post->comments()->save($comment);

        return $this->sendResponse(Constant::$SUCCESS, ['comment_id' => $comment->id]);
    }

    public function removeComment(Request $request){
        $comment = Comment::find($request->input('comment_id'));
        $comment->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    private function buildListPostObject($post){
        return [
            'id' => $post->id,
            'title' => $post->title
        ];
    }

    public function buildPostObject($post)
    {
        $tags = $post->tags()->get()->map(function ($tag) {
            return ['id' => $tag->id, 'title' => $tag->title];
        });

        $writers = $post->writers()->get()->map(function ($writer) {
            return [
                'id' => $writer->id,
                'first_name' => $writer->first_name,
                'last_name' => $writer->last_name,
                'bio' => $writer->bio
            ];
        });

        $contents = $post->post_contents()->get()->map(function ($content) {
            $c = [
                'id' => $content->id,
                'type' => $content->type,
            ];

            switch ($content->type) {
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
                    $c['slides'] = $content->content_slider->content_images()->get()->map(function ($image) {
                        return ["url" => $image->url, "size" => $image->size];
                    });
            }

            return $c;
        });

        return [
            'id' => $post->id,
            'title' => $post->title,
            'score' => $post->score,
            'visits_count' => $post->visits_count,
            'g1' => $post->level_one_group_id,
            'g2' => $post->level_two_group_id,
            'g3' => $post->level_three_group_id,
            "tags" => $tags,
            "validation_status" => $post->validation_status,
            "validation_status_message" => $post->validation_status_message,
            "suggested_courses" => $post->suggested_courses,
            "suggested_posts" => $post->suggested_posts,
            "content_hierarchy" => $post->content_hierarchy,
            "contents" => $contents,
            "writers" => $writers
        ];
    }

}

