<?php


namespace App\Http\Controllers\API\Admin\Posts;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\Admin\GroupsController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\ContentImage;
use App\Models\ContentSlider;
use App\Models\ContentText;
use App\Models\ContentVideo;
use App\Models\ContentVoice;
use App\Models\Course;
use App\Models\Educator;
use App\Models\LevelOneGroup;
use App\Models\LevelThreeGroup;
use App\Models\LevelTwoGroup;
use App\Models\Post;
use App\Models\PostContent;
use App\Models\Tag;
use App\Models\Writer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class PostEditController extends BaseController
{
    public function editPost(Request $request, $ep){
        // TODO some preprocessing

        switch ($ep){
            case Constant::$EDIT_PARAM_COMMENTS_AVAILABILITY:
                return $this->editPostCommentsAvailability($request);
            case Constant::$EDIT_PARAM_COMMENTS_VALIDITY:
                return $this->editPostCommentsValidity($request);;
            case Constant::$EDIT_PARAM_COVER:
                return $this->editPostCover($request);
            case Constant::$EDIT_PARAM_LOGO:
                return $this->editPostLogo($request);
            case Constant::$EDIT_PARAM_TITLE:
                return $this->editPostTitle($request);
            case Constant::$EDIT_PARAM_SUGGESTED_COURSES:
                return $this->editPostSuggestedCourses($request);
            case Constant::$EDIT_PARAM_SUGGESTED_POSTS:
                return $this->editPostSuggestedPosts($request);
            case Constant::$EDIT_PARAM_GROUPS:
                return $this->editPostGroups($request);
            case Constant::$EDIT_PARAM_TAGS:
                return $this->editPostTags($request);
            case Constant::$EDIT_PARAM_CONTENT_VIDEO_ADD:
                return $this->addPostContentVideo($request);
            case Constant::$EDIT_PARAM_CONTENT_VIDEO_UPDATE:
                return $this->updatePostContentVideo($request);
            case Constant::$EDIT_PARAM_CONTENT_VIDEO_DELETE:
                return $this->deletePostContentVideo($request);
            case Constant::$EDIT_PARAM_CONTENT_VOICE_ADD:
                return $this->addPostContentVoice($request);
            case Constant::$EDIT_PARAM_CONTENT_VOICE_UPDATE:
                return $this->updatePostContentVoice($request);
            case Constant::$EDIT_PARAM_CONTENT_VOICE_DELETE:
                return $this->deletePostContentVoice($request);
            case Constant::$EDIT_PARAM_CONTENT_SLIDER_ADD:
                return $this->addPostContentSlider($request);
            case Constant::$EDIT_PARAM_CONTENT_SLIDER_UPDATE:
                return $this->updatePostContentSlider($request);
            case Constant::$EDIT_PARAM_CONTENT_SLIDER_DELETE:
                return $this->deletePostContentSlider($request);
            case Constant::$EDIT_PARAM_CONTENT_TEXT_ADD:
                return $this->addPostContentText($request);
            case Constant::$EDIT_PARAM_CONTENT_TEXT_UPDATE:
                return $this->updatePostContentText($request);
            case Constant::$EDIT_PARAM_CONTENT_TEXT_DELETE:
                return $this->deletePostContentText($request);
            case Constant::$EDIT_PARAM_CONTENT_IMAGE_ADD:
                return $this->addPostContentImage($request);
            case Constant::$EDIT_PARAM_CONTENT_IMAGE_UPDATE:
                return $this->updatePostContentImage($request);
            case Constant::$EDIT_PARAM_CONTENT_IMAGE_DELETE:
                return $this->deletePostContentImage($request);
            case Constant::$EDIT_PARAM_CONTENT_HIERARCHY:
                return $this->editContentHierarchy($request);
            default:
                return $this->sendResponse(Constant::$INVALID_EDIT_TYPE, null);
        }
    }

    public function editPostLogo(Request $request)
    {
        $post = Post::find($request->input('post_id'));
        $action = $request->input('action');
        $file = $request->file('file');

        if($action != Constant::$FILE_ACTION_DELETE) {
            $size = $file->getSize() / 1024;
            if ($size > Constant::$LOGO_SIZE_LIMIT)
                return $this->sendResponse(
                    Constant::$FILE_SIZE_LIMIT_EXCEEDED,
                    ['limit' => Constant::$LOGO_SIZE_NAME_LIMIT . "kb"]
                );
        }

        Helper::uploadFileToDisk(
            $action,
            $post,
            'logo',
            'public',
            'images/post_logos',
            '.png',
            $file
        );

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostCover(Request $request)
    {
        $post = Post::find($request->input('post_id'));
        $action = $request->input('action');
        $file = $request->file('file');

        if($action != Constant::$FILE_ACTION_DELETE) {
            $size = $file->getSize() / 1024;
            if ($size > Constant::$COVER_SIZE_LIMIT)
                return $this->sendResponse(
                    Constant::$FILE_SIZE_LIMIT_EXCEEDED,
                    ['limit' => Constant::$COVER_SIZE_NAME_LIMIT . "kb"]
                );
        }

        Helper::uploadFileToDisk(
            $action,
            $post,
            'cover',
            'public',
            'images/post_covers',
            '.png',
            $file
        );

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostTitle(Request $request)
    {
        $post = Post::find($request->input('post_id'));
        $title = $request->input('title');

        if(!$title)
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $p = Post::where('title', $title)->first();
        if($p && $p->id != $post->id)
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        $post->title = $title;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostCommentsAvailability(Request $request)
    {
        $post = Post::find($request->input('post_id'));

        $open = $request->input('open');
        if(!is_numeric($open)) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post->is_comments_open = $open;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostCommentsValidity(Request $request)
    {
        $post = Post::find($request->input('post_id'));

        $valid = $request->input('valid');
        if(!is_numeric($valid)) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post->all_comments_valid = $valid;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostSuggestedCourses(Request $request)
    {
        $post = Post::find($request->input('post_id'));

        $ids = null;
        if(is_array($request->input('ids')) && sizeof($request->input('ids')) > 0){
            $ids = $request->input('ids');
            $ids = json_encode($ids);
        }

        $post->suggested_courses = $ids;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostSuggestedPosts(Request $request)
    {
        $post = Post::find($request->input('post_id'));

        $ids = null;
        if(is_array($request->input('ids')) && sizeof($request->input('ids')) > 0){
            $ids = $request->input('ids');
            $ids = json_encode($ids);
        }

        $post->suggested_posts = $ids;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostGroups(Request $request)
    {
        $post = Post::find($request->input('post_id'));
        $groups = (object)$request->input('groups');

        if(!$groups)
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // check groups hierarchy
        if(!GroupsController::checkGroupsHierarchy($groups))
            return $this->sendResponse(Constant::$INVALID_GROUP_HIERARCHY, null);

        $post->level_one_group()->dissociate();
        $post->level_two_group()->dissociate();
        $post->level_three_group()->dissociate();
        $post->save();

        // add it to groups
        $g1 = LevelOneGroup::find($groups->g1);
        $g2 = LevelTwoGroup::find($groups->g2);
        $g3 = LevelThreeGroup::find($groups->g3);

        if ($g1) $g1->posts()->save($post);
        if ($g2) $g2->posts()->save($post);
        if ($g3) $g3->posts()->save($post);


        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editContentHierarchy(Request $request)
    {
        $post = Post::find($request->input('post_id'));
        $hierarchy = (array)$request->input('hierarchy');

        if(!$hierarchy)
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // TODO check it's validity

        $post->content_hierarchy = json_encode($hierarchy);
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostTags(Request $request)
    {
        $post = Post::find($request->input('post_id'));
        $tags = (array)$request->input('tags');

        if(!$request->exists('tags'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post->tags()->detach();

        foreach (Tag::find($tags) as $tag) $tag->posts()->save($post);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostWriters(Request $request)
    {
        $post = Post::find($request->input('post_id'));
        $writers = (array)$request->input('writers');

        if(!$request->exists('writers'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post->writers()->detach();

        foreach (Writer::find($writers) as $writer) $post->writers()->save($writer);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addPostContentVideo(Request $request)
    {
        $post = Post::find($request->input('post_id'));

        if(!$request->exists('url') || !$request->exists('size'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_VIDEO;
        $post->post_contents()->save($post_content);

        $content_video = new ContentVideo();
        $content_video->url = $request->input('url');
        $content_video->size = $request->input('size');
        $post_content->content_video()->save($content_video);

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $post_content->id]);
    }

    public function updatePostContentVideo(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        if(!$request->exists('url') || !$request->exists('size'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $content_video = $post_content->content_video()->first();
        $content_video->url = $request->input('url');
        $content_video->size = $request->input('size');
        $content_video->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deletePostContentVideo(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        $post_content->content_video()->delete();
        $post_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addPostContentVoice(Request $request)
    {
        $post = Post::find($request->input('post_id'));

        if(!$request->exists('url') ||
            !$request->exists('size')
        ) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_VOICE;
        $post->post_contents()->save($post_content);

        $content_voice = new ContentVoice();
        $content_voice->url = $request->input('url');
        $content_voice->size = $request->input('size');
        $post_content->content_voice()->save($content_voice);

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $post_content->id]);
    }

    public function updatePostContentVoice(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        if(!$request->exists('url') ||
            !$request->exists('size')
        ) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $content_voice = $post_content->content_voice()->first();
        $content_voice->url = $request->input('url');
        $content_voice->size = $request->input('size');
        $content_voice->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deletePostContentVoice(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        $post_content->content_voice()->delete();
        $post_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addPostContentImage(Request $request)
    {
        $post = Post::find($request->input('post_id'));

        if(!$request->exists('url') ||
            !$request->exists('size')
        ) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_IMAGE;
        $post->post_contents()->save($post_content);

        $content_image = new Contentimage();
        $content_image->url = $request->input('url');
        $content_image->size = $request->input('size');
        $post_content->content_image()->save($content_image);

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $post_content->id]);
    }

    public function updatePostContentImage(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        if(!$request->exists('url') ||
            !$request->exists('size')
        ) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $content_image = $post_content->content_image()->first();
        $content_image->url = $request->input('url');
        $content_image->size = $request->input('size');
        $content_image->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deletePostContentImage(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        $post_content->content_image()->delete();
        $post_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addPostContentText(Request $request)
    {
        $post = Post::find($request->input('post_id'));

        if(!$request->exists('text'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_TEXT;
        $post->post_contents()->save($post_content);

        $content_text = new ContentText();
        $content_text->text = $request->input('text');
        $post_content->content_text()->save($content_text);

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $post_content->id]);
    }

    public function updatePostContentText(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        if(!$request->exists('text'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $content_text = $post_content->content_text()->first();
        $content_text->text = $request->input('text');
        $content_text->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deletePostContentText(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        $post_content->content_text()->delete();
        $post_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addPostContentSlider(Request $request)
    {
        $post = Post::find($request->input('post_id'));

        if(!$request->exists('content') || !$request->exists('title'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // TODO check content validity

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_SLIDER;
        $post->post_contents()->save($post_content);

        $content_slider = new ContentSlider();
        $content_slider->title = $request->input('title');
        $content = (array)$request->input("content");
        $post_content->content_slider()->save($content_slider);

        foreach ($content as $slide){
            $slide = (object)$slide;
            $content_image = new ContentImage();
            $content_image->url = $slide->url;
            $content_image->size = $slide->size;
            $content_slider->content_images()->save($content_image);
        }

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $post_content->id]);
    }

    public function updatePostContentSlider(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        if(!$request->exists('content') || !$request->exists('title'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // TODO check content validity

        $content_slider = $post_content->content_slider()->first();
        $content_slider->title = $request->input('title');
        $content = (array)$request->input("content");

        $content_slider->content_images()->delete();

        foreach ($content as $slide){
            $slide = (object)$slide;
            $content_image = new ContentImage();
            $content_image->url = $slide->url;
            $content_image->size = $slide->size;
            $content_slider->content_images()->save($content_image);
        }

        $content_slider->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deletePostContentSlider(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        $slider = $post_content->content_slider;
        $slider->content_images()->delete();
        $slider->delete();

        $post_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }


}
