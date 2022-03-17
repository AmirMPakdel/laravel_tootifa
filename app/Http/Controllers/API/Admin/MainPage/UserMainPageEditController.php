<?php


namespace App\Http\Controllers\API\Admin\MainPage;

use App\Http\Controllers\API\Admin\GroupsController;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
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
    // TODO disable uploadkey checks when updating content as in course edit

    public function editMainPage(Request $request, $ep)
    {
        // check for maintenance balance
        if ($request->input('user')->u_profile->m_balance < 0)
            return $this->sendResponse(Constant::$NEGETIVE_MAINTANANCE_BALANCE, null);

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
            case Constant::$EDIT_PARAM_COURSE_LIST_TOGGLE_VISIBILITY:
                return $this->toggleMainCourseListVisibility($request);
            case Constant::$EDIT_PARAM_COURSE_LIST_DELETE:
                return $this->deleteMainCourseList($request);
            case Constant::$EDIT_PARAM_POST_LIST_ADD:
                return $this->addMainPostList($request);
            case Constant::$EDIT_PARAM_POST_LIST_UPDATE:
                return $this->updateMainPostList($request);
            case Constant::$EDIT_PARAM_POST_LIST_TOGGLE_VISIBILITY:
                return $this->toggleMainPostListVisibility($request);
            case Constant::$EDIT_PARAM_POST_LIST_DELETE:
                return $this->deleteMainPostList($request);
            case Constant::$EDIT_PARAM_MAIN_FORM_ADD:
                return $this->addMainForm($request);
            case Constant::$EDIT_PARAM_MAIN_FORM_UPDATE:
                return $this->updateMainForm($request);
            case Constant::$EDIT_PARAM_MAIN_FORM_DELETE:
                return $this->deleteMainForm($request);
            case Constant::$EDIT_PARAM_MAIN_INFO_BOX_ADD:
                    return $this->addMainPageInfoBox($request);
            case Constant::$EDIT_PARAM_MAIN_INFO_BOX_UPDATE:
                    return $this->updateMainPageInfoBox($request);
            case Constant::$EDIT_PARAM_MAIN_INFO_BOX_TOGGLE_VISIBILITY:
                    return $this->toggleMainPageInfoBoxVisibility($request);
            case Constant::$EDIT_PARAM_MAIN_INFO_BOX_DELETE:
                    return $this->deleteMainPageInfoBox($request);
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
        $properties->footer_telephones = json_encode($request->input("telephones"));

        $properties->save();

        // TODO check it's validity

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editPropertiesPageCover(Request $request)
    {
        $properties = MainPageProperties::all()[0];

        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $properties->page_cover_title = $request->input("title");
        $properties->page_cover_text = $request->input("text");
        $properties->page_cover_has_link = $request->input("has_link");
        $properties->page_cover_link = $request->input("link");
        $properties->page_cover_link_title = $request->input("link_title");
        $properties->page_cover_template = $request->input("template");

        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $properties,
            "page_cover",
            false,
            false,
            $request->input('upload_key')
        );

        $properties->save();

        return $this->sendResponse($result, null);
    }

    public function editPropertiesPageLogo(Request $request)
    {
        $properties = MainPageProperties::all()[0];

        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $properties,
            "page_logo",
            false,
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function addMainPageInfoBox(Request $request){
        if (
            !$request->exists('title') ||
            !$request->exists('text')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content = new MainContent();
        $main_content->title = $request->input('title');
        $main_content->text = $request->input('text');
        $main_content->has_link = $request->input('has_link');
        $main_content->link = $request->input('link');
        $main_content->link_title = $request->input('link_title');
        $main_content->type = $request->input('type');
        $main_content->visible = $request->input('visible');

        $result = Constant::$SUCCESS;
        switch($main_content->type){
            case Constant::$CONTENT_TYPE_VIDEO:
                $content_video = new ContentVideo();
                $content_video->belongs_to = Constant::$BELONGING_MAIN;
                $result = UploadManager::saveFile(tenant()->id, 1, $content_video, 'url', true, false, $request->input('upload_key'));

                if ($result == Constant::$SUCCESS) {
                    $main_content->save();
                    $main_content->content_video()->save($content_video);
                }
                break;
            case Constant::$CONTENT_TYPE_IMAGE:
                $content_image = new ContentImage();
                $content_image->belongs_to = Constant::$BELONGING_MAIN;
                $result = UploadManager::saveFile(tenant()->id, 1, $content_image, 'url', true, false, $request->input('upload_key'));
        
                if ($result == Constant::$SUCCESS) {
                    $main_content->save();
                    $main_content->content_image()->save($content_image);
                }
                break;
        }

        if ($result == Constant::$SUCCESS) $main_content->save();
        
        return $this->sendResponse($result, ['content_id' => $main_content->id]);
    }

    public function updateMainPageInfoBox(Request $request){
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);
        
        if (!$request->exists('file_state'))
            return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $main_content->title = $request->input('title');
        $main_content->text = $request->input('text');
        $main_content->has_link = $request->input('has_link');
        $main_content->link = $request->input('link');
        $main_content->link_title = $request->input('link_title');
        $main_content->type = $request->input('type');
        $main_content->visible = $request->input('visible');

        $result = Constant::$SUCCESS;
        switch($main_content->type){
            case Constant::$CONTENT_TYPE_VIDEO:
                $content_video = $main_content->content_video()->first();

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
                break;
            case Constant::$CONTENT_TYPE_IMAGE:
                $content_image = $main_content->content_image()->first();

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
                break;
        }

        if ($result == Constant::$SUCCESS) $main_content->save();

        return $this->sendResponse($result, null);
    }

    public function toggleMainPageInfoBoxVisibility(Request $request){
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);
        
        $main_content->visible = $main_content->visible ? 0 : 1;
        $main_content->save();

        return $this->sendResponse(Constant::$SUCCESS, ['visible' => $main_content->visible]);
    }

    public function deleteMainPageInfoBox(Request $request){
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $result = Constant::$SUCCESS;
        switch($main_content->type){
            case Constant::$CONTENT_TYPE_VIDEO:
                $content_video = $main_content->content_video()->first();

                $result = UploadManager::deleteFile(tenant()->id, $content_video->url);
                if ($result == Constant::$SUCCESS) {
                    $content_video->delete();
                }
                break;
            case Constant::$CONTENT_TYPE_IMAGE:
                $content_image = $main_content->content_image()->first();

                $result = UploadManager::deleteFile(tenant()->id, $content_image->url);
                if ($result == Constant::$SUCCESS) {
                    $content_image->delete();
                }
                break;
        }

        if ($result == Constant::$SUCCESS) {
            $main_content->delete();
        }

        return $this->sendResponse($result, null);
    }


    public function addMainContentVideo(Request $request)
    {
        if (
            !$request->exists('title') ||
            !$request->exists('upload_key')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content = new MainContent();
        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->type = Constant::$CONTENT_TYPE_VIDEO;

        $content_video = new ContentVideo();
        $content_video->belongs_to = Constant::$BELONGING_MAIN;
        $result = UploadManager::saveFile(tenant()->id, 1, $content_video, 'url', true, false, $request->input('upload_key'));

        if ($result == Constant::$SUCCESS) {
            $main_content->save();
            $main_content->content_video()->save($content_video);
        }

        return $this->sendResponse($result, ['content_id' => $main_content->id]);
    }

    public function updateMainContentVideo(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('upload_key') ||
            !$request->exists('file_state')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $content_video = $main_content->content_video()->first();

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

        if ($result == Constant::$SUCCESS) $main_content->save();

        return $this->sendResponse($result, null);
    }

    public function deleteMainContentVideo(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);
        
        $content_video = $main_content->content_video()->first();

        $result = UploadManager::deleteFile(tenant()->id, $content_video->url);
        if ($result == Constant::$SUCCESS) {
            $content_video->delete();
            $main_content->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addMainContentVoice(Request $request)
    {
        if (
            !$request->exists('title') ||
            !$request->exists('upload_key')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content = new MainContent();
        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->type = Constant::$CONTENT_TYPE_VOICE;

        $content_voice = new ContentVoice();
        $content_voice->belongs_to = Constant::$BELONGING_MAIN;
        $result = UploadManager::saveFile(tenant()->id, 1, $content_voice, 'url', true, false, $request->input('upload_key'));

        if ($result == Constant::$SUCCESS) {
            $main_content->save();
            $main_content->content_voice()->save($content_voice);
        }

        return $this->sendResponse($result, ['content_id' => $main_content->id]);
    }

    public function updateMainContentVoice(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);
        
        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('upload_key') ||
            !$request->exists('file_state')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $content_voice = $main_content->content_voice()->first();

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

        if ($result == Constant::$SUCCESS) $main_content->save();

        return $this->sendResponse($result, null);
    }

    public function deleteMainContentVoice(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $content_voice = $main_content->content_voice()->first();

        $result = UploadManager::deleteFile(tenant()->id, $content_voice->url);
        if ($result == Constant::$SUCCESS) {
            $content_voice->delete();
            $main_content->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addMainContentImage(Request $request)
    {
        if (
            !$request->exists('title') ||
            !$request->exists('upload_key')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content = new MainContent();
        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $main_content->type = Constant::$CONTENT_TYPE_IMAGE;

        $content_image = new ContentImage();
        $content_image->belongs_to = Constant::$BELONGING_MAIN;
        $result = UploadManager::saveFile(tenant()->id, 1, $content_image, 'url', true, false, $request->input('upload_key'));

        if ($result == Constant::$SUCCESS) {
            $main_content->save();
            $main_content->content_image()->save($content_image);
        }

        return $this->sendResponse($result, ['content_id' => $main_content->id]);
    }

    public function updateMainContentImage(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (
            !$request->exists('title') ||
            !$request->exists('link') ||
            !$request->exists('upload_key') ||
            !$request->exists('file_state')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_content->title = $request->input('title');
        $main_content->link = $request->input('link');
        $content_image = $main_content->content_image()->first();

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

        if ($result == Constant::$SUCCESS) $main_content->save();

        return $this->sendResponse($result, null);
    }

    public function deleteMainContentImage(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $content_image = $main_content->content_image()->first();

        $result = UploadManager::deleteFile(tenant()->id, $content_image->url);
        if ($result == Constant::$SUCCESS) {
            $content_image->delete();
            $main_content->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addMainContentText(Request $request)
    {
        if (
            !$request->exists('title') ||
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
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

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
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $main_content->content_text()->delete();
        $main_content->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addMainContentSlider(Request $request)
    {
        if (
            !$request->exists('content') ||
            !$request->exists('title')
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

        foreach ($content as $uk) {
            $content_image = new ContentImage();
            $content_image->belongs_to = Constant::$BELONGING_MAIN;
            $result = UploadManager::saveFile(tenant()->id, 1, $content_image, 'url', true, false, $uk);

            if ($result == Constant::$SUCCESS) {
                $main_content->save();
                $main_content->content_image()->save($content_image);
            }

            $content_slider->content_images()->save($content_image);
        }

        return $this->sendResponse(Constant::$SUCCESS, ['content_id' => $main_content->id]);
    }

    public function updateMainContentSlider(Request $request)
    {
        $main_content = MainContent::find($request->input('content_id'));
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

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
        if (!$main_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

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
        if (!$main_form) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

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
        if (!$main_form) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $main_form->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addMainCourseList(Request $request)
    {
        $main_course_list = new MainCourseList();
        $main_course_list->title = $request->input('title');
        $main_course_list->list = $request->input('list');
        $main_course_list->default_type = $request->input('default_type');
        $main_course_list->visible = $request->input('visible');

        $groups = (object)$request->input('groups');

        // check groups hierarchy
        if (isset($groups->g1) && isset($groups->g2) && isset($groups->g3)) {
            if (!GroupsController::checkGroupsHierarchy($groups))
                return $this->sendResponse(Constant::$INVALID_GROUP_HIERARCHY, null);
        }

        $main_course_list->level_one_group_id = $groups->g1;
        $main_course_list->level_two_group_id = $groups->g2;
        $main_course_list->level_three_group_id = $groups->g3;

        $main_course_list->save();

        return $this->sendResponse(Constant::$SUCCESS, ['list_id' => $main_course_list->id]);
    }

    public function updateMainCourseList(Request $request)
    {
        $main_course_list = MainCourseList::find($request->input('list_id'));
        if (!$main_course_list) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $main_course_list->title = $request->input('title');
        $main_course_list->list = $request->input('list');
        $main_course_list->default_type = $request->input('default_type');
        $main_course_list->visible = $request->input('visible');

        $groups = (object)$request->input('groups');

        // check groups hierarchy
        if (isset($groups->g1) && isset($groups->g2) && isset($groups->g3)) {
            if (!GroupsController::checkGroupsHierarchy($groups))
                return $this->sendResponse(Constant::$INVALID_GROUP_HIERARCHY, null);
        }

        $main_course_list->level_one_group_id = $groups->g1;
        $main_course_list->level_two_group_id = $groups->g2;
        $main_course_list->level_three_group_id = $groups->g3;

        
        $main_course_list->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function toggleMainCourseListVisibility(Request $request){
        $main_course_list = MainCourseList::find($request->input('list_id'));
        if (!$main_course_list) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);
        
        $main_course_list->visible = $main_course_list->visible ? 0 : 1;
        $main_course_list->save();

        return $this->sendResponse(Constant::$SUCCESS, ['visible' => $main_course_list->visible]);
    }

    public function deleteMainCourseList(Request $request)
    {
        $main_course_list = MainCourseList::find($request->input('list_id'));
        if (!$main_course_list) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);
        
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
        $main_post_list->visible = $request->input('visible');
        $main_post_list->default_type = $request->input('default_type');
        $main_post_list->save();

        return $this->sendResponse(Constant::$SUCCESS, ['list_id' => $main_post_list->id]);
    }

    public function updateMainPostList(Request $request)
    {
        $main_post_list = MainPostList::find($request->input('list_id'));
        if (!$main_post_list) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (
            !$request->exists('title') ||
            !$request->exists('list') ||
            !$request->exists('default_type')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $main_post_list->title = $request->input('title');
        $main_post_list->list = $request->input('list');
        $main_post_list->visible = $request->input('visible');
        $main_post_list->default_type = $request->input('default_type');
        $main_post_list->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function toggleMainPostListVisibility(Request $request){
        $main_post_list = MainPostList::find($request->input('list_id'));
        if (!$main_post_list) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);
        
        $main_post_list->visible = $main_post_list->visible ? 0 : 1;
        $main_post_list->save();

        return $this->sendResponse(Constant::$SUCCESS, ['visible' => $main_post_list->visible]);
    }

    public function deleteMainPostList(Request $request)
    {
        $main_post_list = MainPostList::find($request->input('list_id'));
        if (!$main_post_list) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);
        
        $main_post_list->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }
}
