<?php


namespace App\Http\Controllers\API\Admin\MainPage;

use App\Http\Controllers\API\Admin\UploadController;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Includes\UploadManager;
use App\Models\ContentImage;
use App\Models\ContentSlider;
use App\Models\ContentText;
use App\Models\ContentVideo;
use App\Models\ContentVoice;
use App\Models\MainContent;
use App\Models\MainCourseList;
use App\Models\MainForm;
use App\Models\MainPageProperties;
use App\Models\MainPostList;
use Illuminate\Http\Request;


class UserMainPageEditController extends BaseController
{
    public function editMainPage(Request $request, $ep)
    {
        // TODO some preprocessing

        switch ($ep) {
            case Constant::$EDIT_PARAM_TITLE:
                return $this->editPropertiesTitle($request);
            case Constant::$EDIT_PARAM_STORE_OPEN:
                return $this->editPropertiesStoreOpen($request);
            case Constant::$EDIT_PARAM_BLOG_OPEN:
                return $this->editPropertiesBlogOpen($request);
            case Constant::$EDIT_PARAM_CONTENT_HIERARCHY:
                return $this->editPropertiesContentHierarchy($request);
            case Constant::$EDIT_PARAM_BANNER_TEXT:
                return $this->editPropertiesBannerText($request);
            case Constant::$EDIT_PARAM_BANNER_LINK:
                return $this->editPropertiesBannerLink($request);
            case Constant::$EDIT_PARAM_BANNER_COVER:
                return $this->editPropertiesBannerCover($request);
            case Constant::$EDIT_PARAM_FOOTER_LINKS:
                return $this->editPropertiesFooterLinks($request);
            case Constant::$EDIT_PARAM_BANNER_STATUS:
                return $this->editPropertiesBannerStatus($request);
            case Constant::$EDIT_PARAM_LOGO:
                return $this->editPropertiesPageLogo($request);
            case Constant::$EDIT_PARAM_COVER:
                return $this->editPropertiesPageCover($request);
            case Constant::$EDIT_PARAM_CONTENT_VIDEO_ADD:
                return $this->addMainContentVideo($request);
            case Constant::$EDIT_PARAM_CONTENT_VIDEO_UPDATE:
                return $this->updateMainContentVideo($request);
            case Constant::$EDIT_PARAM_CONTENT_VIDEO_DELETE:
                return $this->deleteMainContentVideo($request);
            case Constant::$EDIT_PARAM_CONTENT_VOICE_ADD:
                return $this->addMainContentVoice($request);
            case Constant::$EDIT_PARAM_CONTENT_VOICE_UPDATE:
                return $this->updateMainContentVoice($request);
            case Constant::$EDIT_PARAM_CONTENT_VOICE_DELETE:
                return $this->deleteMainContentVoice($request);
            case Constant::$EDIT_PARAM_CONTENT_SLIDER_ADD:
                return $this->addMainContentSlider($request);
            case Constant::$EDIT_PARAM_CONTENT_SLIDER_UPDATE:
                return $this->updateMainContentSlider($request);
            case Constant::$EDIT_PARAM_CONTENT_SLIDER_DELETE:
                return $this->deleteMainContentSlider($request);
            case Constant::$EDIT_PARAM_CONTENT_TEXT_ADD:
                return $this->addMainContentText($request);
            case Constant::$EDIT_PARAM_CONTENT_TEXT_UPDATE:
                return $this->updateMainContentText($request);
            case Constant::$EDIT_PARAM_CONTENT_TEXT_DELETE:
                return $this->deleteMainContentText($request);
            case Constant::$EDIT_PARAM_CONTENT_IMAGE_ADD:
                return $this->addMainContentImage($request);
            case Constant::$EDIT_PARAM_CONTENT_IMAGE_UPDATE:
                return $this->updateMainContentImage($request);
            case Constant::$EDIT_PARAM_CONTENT_IMAGE_DELETE:
                return $this->deleteMainContentImage($request);
            case Constant::$EDIT_PARAM_COURSE_LIST_ADD:
                return $this->addMainCourseList($request);
            case Constant::$EDIT_PARAM_COURSE_LIST_UPDATE:
                return $this->updateMainCourseList($request);
            case Constant::$EDIT_PARAM_COURSE_LIST_DELETE:
                return $this->deleteMainCourseList($request);
            case Constant::$EDIT_PARAM_POST_LIST_ADD:
                return $this->addMainPostList($request);
            case Constant::$EDIT_PARAM_POST_LIST_UPDATE:
                return $this->updateMainPostList($request);
            case Constant::$EDIT_PARAM_POST_LIST_DELETE:
                return $this->deleteMainPostList($request);
            case Constant::$EDIT_PARAM_MAIN_FORM_ADD:
                return $this->addMainForm($request);
            case Constant::$EDIT_PARAM_MAIN_FORM_UPDATE:
                return $this->updateMainForm($request);
            case Constant::$EDIT_PARAM_MAIN_FORM_DELETE:
                return $this->deleteMainForm($request);
            default:
                return $this->sendResponse(Constant::$INVALID_EDIT_TYPE, null);
        }
    }

    public function editPropertiesTitle(Request $request)
    {
        $properties = MainPageProperties::all()[0];
        $properties->page_title = $request->input("title");
        $properties->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPropertiesStoreOpen(Request $request)
    {
        $properties = MainPageProperties::all()[0];
        $properties->store_open = $request->input("store_open");
        $properties->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPropertiesBlogOpen(Request $request)
    {
        $properties = MainPageProperties::all()[0];
        $properties->blog_open = $request->input("blog_open");
        $properties->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPropertiesBannerStatus(Request $request)
    {
        $properties = MainPageProperties::all()[0];
        $properties->is_banner_on = $request->input("is_banner_on");
        $properties->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPropertiesBannerText(Request $request)
    {
        $properties = MainPageProperties::all()[0];
        $properties->banner_text = $request->input("text");
        $properties->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPropertiesBannerLink(Request $request)
    {
        $properties = MainPageProperties::all()[0];
        $properties->banner_link = $request->input("link");
        $properties->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPropertiesBannerCover(Request $request)
    {
        $properties = MainPageProperties::all()[0];

        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $properties,
            "banner_cover",
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function editPropertiesContentHierarchy(Request $request)
    {
        $properties = MainPageProperties::all()[0];
        $properties->content_hierarchy = json_encode($request->input("hierarchy"));
        $properties->save();

        // TODO check it's validity

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPropertiesFooterLinks(Request $request)
    {
        $properties = MainPageProperties::all()[0];
        $properties->footer_links = json_encode($request->input("links"));
        $properties->save();

        // TODO check it's validity

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPropertiesPageCover(Request $request)
    {
        $properties = MainPageProperties::all()[0];

        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $result = $this->uc->updateFileState(
            $file_state,
            tenant()->id,
            1,
            $properties,
            "page_cover",
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function editPropertiesPageLogo(Request $request)
    {
        $properties = MainPageProperties::all()[0];

        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $result = $this->uc->updateFileState(
            $file_state,
            tenant()->id,
            1,
            $properties,
            "page_logo",
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function addMainContentVideo(Request $request)
    {
        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('upload_key')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content = new MainContent();
        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->type = Constant::$CONTENT_TYPE_VIDEO;
        $main_content->save();

        $content_video = new ContentVideo();
        $content_video->belongs_to = Constant::$BELONGING_MAIN;
        $main_content->content_video()->save($content_video);
        $this->uc->saveFile(tenant()->id, 1, $content_video, 'url', true, $request->input('upload_key'));

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $main_content->id]);
    }

    public function updateMainContentVideo(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('upload_key') ||
            !$request->exists('file_state')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->save();

        $content_video = $main_content->content_video()->first();
        $content_video->url = $request->input('url');
        $content_video->size = $request->input('size');
        $content_video->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteMainContentVideo(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        $main_content->content_video()->delete();
        $main_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addMainContentVoice(Request $request)
    {
        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('url') ||
            !$request->exists('size')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content = new MainContent();
        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->type = Constant::$CONTENT_TYPE_VOICE;
        $main_content->save();

        $content_voice = new ContentVoice();
        $content_voice->url = $request->input('url');
        $content_voice->size = $request->input('size');
        $content_voice->belongs_to = Constant::$BELONGING_MAIN;
        $main_content->content_voice()->save($content_voice);

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $main_content->id]);
    }

    public function updateMainContentVoice(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('url') ||
            !$request->exists('size')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->save();

        $content_voice = $main_content->content_voice()->first();
        $content_voice->url = $request->input('url');
        $content_voice->size = $request->input('size');
        $content_voice->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteMainContentVoice(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        $main_content->content_voice()->delete();
        $main_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addMainContentImage(Request $request)
    {
        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('url') ||
            !$request->exists('size')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content = new MainContent();
        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->type = Constant::$CONTENT_TYPE_IMAGE;
        $main_content->save();

        $content_image = new ContentImage();
        $content_image->url = $request->input('url');
        $content_image->size = $request->input('size');
        $content_image->belongs_to = Constant::$BELONGING_MAIN;
        $main_content->content_image()->save($content_image);

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $main_content->id]);
    }

    public function updateMainContentImage(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('url') ||
            !$request->exists('size')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->save();

        $content_image = $main_content->content_image()->first();
        $content_image->url = $request->input('url');
        $content_image->size = $request->input('size');
        $content_image->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteMainContentImage(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        $main_content->content_image()->delete();
        $main_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addMainContentText(Request $request)
    {
        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('text')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content = new MainContent();
        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->type = Constant::$CONTENT_TYPE_TEXT;
        $main_content->save();

        $content_text = new ContentText();
        $content_text->text = $request->input('text');
        $content_text->belongs_to = Constant::$BELONGING_MAIN;
        $main_content->content_text()->save($content_text);

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $main_content->id]);
    }

    public function updateMainContentText(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('text')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->save();

        $content_text = new ContentText();
        $content_text->text = $request->input('text');
        $main_content->content_text()->save($content_text);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteMainContentText(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        $main_content->content_text()->delete();
        $main_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addMainContentSlider(Request $request)
    {
        if (
            !$request->exists('content') ||
            !$request->exists('title') ||
            !$request->exists('link')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // TODO check content validity

        $main_content = new MainContent();
        $main_content->type = Constant::$CONTENT_TYPE_SLIDER;
        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->save();

        $content_slider = new ContentSlider();
        $content_slider->title = $request->input('title');
        $content_slider->belongs_to = Constant::$BELONGING_MAIN;
        $content = (array)$request->input("content");
        $main_content->content_slider()->save($content_slider);

        foreach ($content as $slide) {
            $slide = (object)$slide;
            $content_image = new ContentImage();
            $content_image->url = $slide->url;
            $content_image->size = $slide->size;
            $content_image->belongs_to = Constant::$BELONGING_MAIN;
            $content_slider->content_images()->save($content_image);
        }

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $main_content->id]);
    }

    public function updateMainContentSlider(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        if (
            !$request->exists('content') ||
            !$request->exists('title') ||
            !$request->exists('link')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // TODO check content validity

        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->save();

        $content_slider = $main_content->content_slider()->first();
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

    public function deleteMainContentSlider(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));

        $slider = $main_content->content_slider;
        $slider->content_images()->delete();
        $slider->delete();

        $main_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addMainForm(Request $request)
    {
        if (!$request->exists('title')) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_form = new MainForm();
        $main_form->title = $request->input('title');
        $main_form->text = $request->input('text');
        $main_form->submit_text = $request->input('submit_text');
        $main_form->has_email_input = $request->input('has_email_input');
        $main_form->has_name_input = $request->input('has_name_input');
        $main_form->has_phone_input = $request->input('has_phone_input');
        $main_form->has_city_input = $request->input('has_city_input');
        $main_form->has_province_input = $request->input('has_province_input');
        $main_form->save();

        return $this->sendResponse(Constant::$SUCCESS, ['form_id' => $main_form->id]);
    }

    public function updateMainForm(Request $request)
    {
        $main_form = MainForm::find($request->input('form_id'));

        if (!$request->exists('title')) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_form->title = $request->input('title');
        $main_form->text = $request->input('text');
        $main_form->submit_text = $request->input('submit_text');
        $main_form->has_email_input = $request->input('has_email_input');
        $main_form->has_name_input = $request->input('has_name_input');
        $main_form->has_phone_input = $request->input('has_phone_input');
        $main_form->has_city_input = $request->input('has_city_input');
        $main_form->has_province_input = $request->input('has_province_input');
        $main_form->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteMainForm(Request $request)
    {
        $main_form = MainForm::find($request->input('form_id'));
        $main_form->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addMainCourseList(Request $request)
    {
        if (
            !$request->exists('title') ||
            !$request->exists('list') ||
            !$request->exists('default_type')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_course_list = new MainCourseList();
        $main_course_list->title = $request->input('title');
        $main_course_list->list = $request->input('list');
        $main_course_list->default_type = $request->input('default_type');
        $main_course_list->save();

        return $this->sendResponse(Constant::$SUCCESS, ['list_id' => $main_course_list->id]);
    }

    public function updateMainCourseList(Request $request)
    {
        $main_course_list = MainCourseList::find($request->input('list_id'));

        if (
            !$request->exists('title') ||
            !$request->exists('list') ||
            !$request->exists('default_type')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_course_list->title = $request->input('title');
        $main_course_list->list = $request->input('list');
        $main_course_list->default_type = $request->input('default_type');
        $main_course_list->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteMainCourseList(Request $request)
    {
        $main_course_list = MainCourseList::find($request->input('list_id'));
        $main_course_list->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addMainPostList(Request $request)
    {
        if (
            !$request->exists('title') ||
            !$request->exists('list') ||
            !$request->exists('default_type')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_post_list = new MainPostList();
        $main_post_list->title = $request->input('title');
        $main_post_list->list = $request->input('list');
        $main_post_list->default_type = $request->input('default_type');
        $main_post_list->save();

        return $this->sendResponse(Constant::$SUCCESS, ['list_id' => $main_post_list->id]);
    }

    public function updateMainPostList(Request $request)
    {
        $main_post_list = MainPostList::find($request->input('list_id'));

        if (
            !$request->exists('title') ||
            !$request->exists('list') ||
            !$request->exists('default_type')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_post_list->title = $request->input('title');
        $main_post_list->list = $request->input('list');
        $main_post_list->default_type = $request->input('default_type');
        $main_post_list->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteMainPostList(Request $request)
    {
        $main_post_list = MainPostList::find($request->input('list_id'));
        $main_post_list->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }
}
