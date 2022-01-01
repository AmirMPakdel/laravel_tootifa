<?php


namespace App\Http\Controllers\API\Admin\Posts;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\Admin\GroupsController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Includes\UploadManager;
use App\Models\ContentImage;
use App\Models\ContentSlider;
use App\Models\ContentText;
use App\Models\ContentVideo;
use App\Models\ContentVoice;
use App\Models\LevelOneGroup;
use App\Models\LevelThreeGroup;
use App\Models\LevelTwoGroup;
use App\Models\Post;
use App\Models\PostContent;
use App\Models\PostForm;
use App\Models\Tag;
use App\Models\Writer;
use Illuminate\Http\Request;


class PostEditController extends BaseController
{
    // TODO disable uploadkey checks when updating content as in course edit

    public function editPost(Request $request, $ep)
    {
        // check for maintenance balance
        if ($request->input('user')->u_profile->m_balance < 0)
            return $this->sendResponse(Constant::$NEGETIVE_MAINTANANCE_BALANCE, null);

        $post = Post::find($request->input('post_id'));
        if (!$post) return $this->sendResponse(Constant::$POST_NOT_FOUND, null);

        $request->request->add(['$post' => $post]);

        switch ($ep) {
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
            case Constant::$EDIT_PARAM_POST_FORM_ADD:
                return $this->addPostForm($request);
            case Constant::$EDIT_PARAM_POST_FORM_UPDATE:
                return $this->updatePostForm($request);
            case Constant::$EDIT_PARAM_POST_FORM_DELETE:
                return $this->deletePostForm($request);
            default:
                return $this->sendResponse(Constant::$INVALID_EDIT_TYPE, null);
        }
    }

    public function editPostLogo(Request $request)
    {
        $post = $request->input('post');
        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $post,
            "logo",
            false,
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function editPostCover(Request $request)
    {
        $post = $request->input('post');
        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $post,
            "cover",
            false,
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function editPostTitle(Request $request)
    {
        $post = $request->input('post');
        $title = $request->input('title');

        if (!$title)
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $p = Post::where('title', $title)->first();
        if ($p && $p->id != $post->id)
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        $post->title = $title;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostCommentsAvailability(Request $request)
    {
        $post = $request->input('post');

        $open = $request->input('open');
        if (!is_numeric($open)) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post->is_comments_open = $open;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostCommentsValidity(Request $request)
    {
        $post = $request->input('post');

        $valid = $request->input('valid');
        if (!is_numeric($valid)) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post->all_comments_valid = $valid;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostSuggestedCourses(Request $request)
    {
        $post = $request->input('post');

        $ids = null;
        if (is_array($request->input('ids')) && sizeof($request->input('ids')) > 0) {
            $ids = $request->input('ids');
            $ids = json_encode($ids);
        }

        $post->suggested_courses = $ids;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostSuggestedPosts(Request $request)
    {
        $post = $request->input('post');

        $ids = null;
        if (is_array($request->input('ids')) && sizeof($request->input('ids')) > 0) {
            $ids = $request->input('ids');
            $ids = json_encode($ids);
        }

        $post->suggested_posts = $ids;
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostGroups(Request $request)
    {
        $post = $request->input('post');
        $groups = (object)$request->input('groups');

        if (!$groups)
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // check groups hierarchy
        if (!GroupsController::checkGroupsHierarchy($groups))
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
        $post = $request->input('post');
        $hierarchy = (array)$request->input('hierarchy');

        if (!$hierarchy)
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // TODO check it's validity

        $post->content_hierarchy = json_encode($hierarchy);
        $post->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostTags(Request $request)
    {
        $post = $request->input('post');
        $tags = (array)$request->input('tags');

        if (!$request->exists('tags'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post->tags()->detach();

        foreach (Tag::find($tags) as $tag) $tag->posts()->save($post);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPostWriters(Request $request)
    {
        $post = $request->input('post');
        $writers = (array)$request->input('writers');

        if (!$request->exists('writers'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post->writers()->detach();

        foreach (Writer::find($writers) as $writer) $post->writers()->save($writer);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addPostContentVideo(Request $request)
    {
        $post = $request->input('post');

        if (!$request->exists('upload_key'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_VIDEO;

        $content_video = new ContentVideo();
        $content_video->belongs_to = Constant::$BELONGING_POST;
        $result = UploadManager::saveFile(
            tenant()->id,
            1,
            $content_video,
            'url',
            true,
            false,
            $request->input('upload_key')
        );

        if ($result == Constant::$SUCCESS) {
            $post->post_contents()->save($post_content);
            $post_content->content_video()->save($content_video);
        }

        return $this->sendResponse($result, ['content_id' => $post_content->id]);
    }

    public function updatePostContentVideo(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));
        if (!$post_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (!$request->exists('upload_key'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $content_video = $post_content->content_video()->first();
        $result = UploadManager::updateFileState(
            $request->input('file_state'),
            tenant()->id,
            1,
            $content_video,
            "url",
            true,
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function deletePostContentVideo(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));
        if (!$post_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $content_video = $post_content->content_video()->first();
        $result = UploadManager::deleteFile(tenant()->id, $content_video->url);

        if ($result == Constant::$SUCCESS) {
            $content_video->delete();
            $post_content->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addPostContentVoice(Request $request)
    {
        $post = $request->input('post');

        if (!$request->exists('upload_key'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_VOICE;

        $content_voice = new ContentVoice();
        $content_voice->belongs_to = Constant::$BELONGING_POST;
        $result = UploadManager::saveFile(
            tenant()->id,
            1,
            $content_voice,
            'url',
            true,
            false,
            $request->input('upload_key')
        );

        if ($result == Constant::$SUCCESS) {
            $post->post_contents()->save($post_content);
            $post_content->content_voice()->save($content_voice);
        }

        return $this->sendResponse($result, ['content_id' => $post_content->id]);
    }

    public function updatePostContentVoice(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));
        if (!$post_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (!$request->exists('upload_key'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $content_voice = $post_content->content_voiceo()->first();
        $result = UploadManager::updateFileState(
            $request->input('file_state'),
            tenant()->id,
            1,
            $content_voice,
            "url",
            true,
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function deletePostContentVoice(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));
        if (!$post_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $content_voice = $post_content->content_voice()->first();
        $result = UploadManager::deleteFile(tenant()->id, $content_voice->url);

        if ($result == Constant::$SUCCESS) {
            $content_voice->delete();
            $post_content->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addPostContentImage(Request $request)
    {
        $post = $request->input('post');

        if (!$request->exists('upload_key'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_IMAGE;

        $content_image = new ContentImage();
        $content_image->belongs_to = Constant::$BELONGING_POST;
        $result = UploadManager::saveFile(
            tenant()->id,
            1,
            $content_image,
            'url',
            true,
            false,
            $request->input('upload_key')
        );

        if ($result == Constant::$SUCCESS) {
            $post->post_contents()->save($post_content);
            $post_content->content_image()->save($content_image);
        }

        return $this->sendResponse($result, ['content_id' => $post_content->id]);
    }

    public function updatePostContentImage(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));
        if (!$post_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (!$request->exists('upload_key'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $content_image = $post_content->content_image()->first();
        $result = UploadManager::updateFileState(
            $request->input('file_state'),
            tenant()->id,
            1,
            $content_image,
            "url",
            true,
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function deletePostContentImage(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));
        if (!$post_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $content_image = $post_content->content_image()->first();
        $result = UploadManager::deleteFile(tenant()->id, $content_image->url);

        if ($result == Constant::$SUCCESS) {
            $content_image->delete();
            $post_content->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addPostContentText(Request $request)
    {
        $post = $request->input('post');

        if (!$request->exists('text'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_TEXT;
        $post->post_contents()->save($post_content);

        $content_text = new ContentText();
        $content_text->text = $request->input('text');
        $content_text->belongs_to = Constant::$BELONGING_POST;
        $post_content->content_text()->save($content_text);

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $post_content->id]);
    }

    public function updatePostContentText(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        if (!$request->exists('text'))
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
        $post = $request->input('post');

        if (!$request->exists('content') || !$request->exists('title'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // TODO check content validity

        $post_content = new PostContent();
        $post_content->type = Constant::$CONTENT_TYPE_SLIDER;
        $post->post_contents()->save($post_content);

        $content_slider = new ContentSlider();
        $content_slider->title = $request->input('title');
        $content_slider->belongs_to = Constant::$BELONGING_POST;
        $content = (array)$request->input("content");
        $post_content->content_slider()->save($content_slider);

        foreach ($content as $slide) {
            $slide = (object)$slide;
            $content_image = new ContentImage();
            $content_image->url = $slide->url;
            $content_image->size = $slide->size;
            $content_image->belongs_to = Constant::$BELONGING_POST;
            $content_slider->content_images()->save($content_image);
        }

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $post_content->id]);
    }

    public function updatePostContentSlider(Request $request)
    {
        $post_content = PostContent::find($request->input('content_id'));

        if (!$request->exists('content') || !$request->exists('title'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // TODO check content validity

        $content_slider = $post_content->content_slider()->first();
        $content_slider->title = $request->input('title');
        $content = (array)$request->input("content");

        $content_slider->content_images()->delete();

        foreach ($content as $slide) {
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

    public function addPostForm(Request $request)
    {
        $post = $request->input('post');
        if (!$request->exists('title')) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $post_form = new PostForm();
        $post_form->title = $request->input('title');
        $post_form->text = $request->input('text');
        $post_form->submit_text = $request->input('submit_text');
        $post_form->has_email_input = $request->input('has_email_input');
        $post_form->has_name_input = $request->input('has_name_input');
        $post_form->has_phone_input = $request->input('has_phone_input');
        $post_form->has_city_input = $request->input('has_city_input');
        $post_form->has_province_input = $request->input('has_province_input');
        $post_form->post_id = $post->id;
        $post_form->save();

        return $this->sendResponse(Constant::$SUCCESS, ['form_id' => $post_form->id]);
    }

    public function updatePostForm(Request $request)
    {
        $post_form = PostForm::find($request->input('form_id'));

        $post_form->title = $request->input('title');
        $post_form->text = $request->input('text');
        $post_form->submit_text = $request->input('submit_text');
        $post_form->has_email_input = $request->input('has_email_input');
        $post_form->has_name_input = $request->input('has_name_input');
        $post_form->has_phone_input = $request->input('has_phone_input');
        $post_form->has_city_input = $request->input('has_city_input');
        $post_form->has_province_input = $request->input('has_province_input');
        $post_form->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deletePostForm(Request $request)
    {
        $post_form = PostForm::find($request->input('form_id'));
        $post_form->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }
}
