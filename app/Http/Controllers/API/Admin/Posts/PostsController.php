<?php


namespace App\Http\Controllers\API\Admin\Posts;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\Admin\GroupsController;
use App\Includes\Constant;
use App\Models\LevelOneGroup;
use App\Models\LevelThreeGroup;
use App\Models\LevelTwoGroup;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;


class PostsController extends BaseController
{
    public function createPost(Request $request)
    {
        // fetching data
        $title = $request->input('title');
        $groups = (object)$request->input('groups');
        $tags = (array)$request->input('tags');

        // check for maintenance balance
        if ($request->input('user')->u_profile->m_balance < 0)
            return $this->sendResponse(Constant::$NEGETIVE_MAINTANANCE_BALANCE, null);

        // check title
        if (Post::where('title', $title)->exists())
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        // check groups hierarchy
        if (!GroupsController::checkGroupsHierarchy($groups))
            return $this->sendResponse(Constant::$INVALID_GROUP_HIERARCHY, null);

        // create post
        $post = new Post();
        $post->title = $title;

        // add it to tags
        foreach (Tag::find($tags) as $tag)
            $tag->posts()->save($post);

        // add it to groups
        $g1 = LevelOneGroup::find($groups->g1);
        $g2 = LevelTwoGroup::find($groups->g2);
        $g3 = LevelThreeGroup::find($groups->g3);

        if ($g1) $g1->posts()->save($post);
        if ($g2) $g2->posts()->save($post);
        if ($g3) $g3->posts()->save($post);

        $post->save();

        return $this->sendResponse(Constant::$SUCCESS,  ['post_id' => $post->id]);
    }


    public function fetchPosts(Request $request, $chunk_count, $page_count)
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
            $paginator = $group->posts()->where($query)->orderBy($order_by, $order_direction)
                ->paginate($chunk_count, ['*'], 'page', $page_count);
        } else {
            $paginator = Post::where($query)->orderBy($order_by, $order_direction)
                ->paginate($chunk_count, ['*'], 'page', $page_count);
        }

        $posts = $paginator->map(function ($post) {
            return $this->buildListPostObject($post);
        });

        if (sizeof($posts) == 0) return $this->sendResponse(Constant::$NO_DATA, null);
        $result = ["total_size" => $paginator->total(), "list" => $posts];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function fetchSpecificPosts(Request $request)
    {
        $ids = (array)$request->input('ids');

        $posts = Post::find($ids)->map(function ($post) {
            return $this->buildListPostObject($post);
        })->toArray();

        return $this->sendResponse(Constant::$SUCCESS, $posts);
    }

    public function loadPost(Request $request)
    {

        $post = Post::where('id', $request->input('post_id'))->get()->map(function ($post) {
            return $this->buildPostObject($post);
        })->toArray()[0];

        return $this->sendResponse(Constant::$SUCCESS, $post);
    }

    public function getLogo(Request $request, $post_id)
    {
        $post = Post::find($post_id);
        if ($post && $post->logo) {
            $path = storage_path("app\public\\" . $post->logo);
            $path = str_replace('/', '\\', $path);
            $headers = array(
                'Content-Type' => 'image/png',
            );

            return response()->file($path, $headers);
        } else return null;
    }

    public function getCover(Request $request, $post_id)
    {
        $post = Post::find($post_id);
        if ($post && $post->cover) {
            $path = storage_path("app\public\\" . $post->cover);
            $path = str_replace('/', '\\', $path);
            $headers = array(
                'Content-Type' => 'image/png',
            );

            return response()->file($path, $headers);
        } else return null;
    }

    private function buildListPostObject($post)
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'score' => $post->score,
            'visits' => $post->visits_count,
            'g1' => $post->level_one_group_id,
            'g2' => $post->level_two_group_id,
            'g3' => $post->level_three_group_id,
            'validation_status' => $post->validation_status
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

        $post_forms = $post->post_forms()->map(function ($post_form) {
            return [
                'title' => $post_form->title,
                'text' => $post_form->text,
                'submit_text' => $post_form->submit_text,
                'has_email_input' => $post_form->has_email_input,
                'has_name_input' => $post_form->has_name_input,
                'has_phone_input' => $post_form->has_phone_input,
                'has_city_input' => $post_form->has_city_input,
                'has_province_input' => $post_form->has_province_input
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
            "writers" => $writers,
            "post_forms" => $post_forms,
            "logo" => $post->logo,
            "cover" => $post->cover
        ];
    }
}
