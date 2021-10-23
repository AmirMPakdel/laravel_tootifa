<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\Post;
use App\Models\LevelOneGroup;
use App\Models\LevelTwoGroup;
use App\Models\LevelThreeGroup;
use Exception;
use Illuminate\Http\Request;


class BlogController extends BaseController
{
    
    public function fetchPosts(Request $request, $chunk_count, $page_count)
    {
        $filters = (object)$request->input('filters');
        $sorting_mode = $request->input('sorting_mode');

        $search_phrase = $filters->search_phrase;
        $group = (object)$filters->group;

        // which group
        if (isset($group->level)) {
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

        if ($group)
            $posts = $group->posts()->valid()->where($query)
                ->orderBy($order_by, $order_direction)
                ->get()->map(function ($post) {
                    return $this->buildListPostObject($post);
                })->toArray();
        else
            $posts = Post::valid()->where($query)->orderBy($order_by, $order_direction)
                ->get()->map(function ($post) {
                    return $this->buildListPostObject($post);
                })->toArray();

        try {
            $last_items = (collect($posts)->sortByDesc('id')->chunk($chunk_count))[$page_count];
            return $this->sendResponse(Constant::$SUCCESS, $last_items);
        } catch (Exception $e) {
            return $this->sendResponse(Constant::$NO_DATA, null);
        }
    }

    public function loadPost(Request $request){
        // set visit 
        Helper::setPostVisit($request->input('post_id'));

        $post = Post::where('id',$request->input('post_id'))->get()->map(function ($post) {
            return $this->buildPostObject($post);
        })->toArray()[0];

        return $this->sendResponse(Constant::$SUCCESS, $post);
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
