<?php

namespace App\Http\Controllers\API\Admin\Courses;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\Admin\GroupsController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Includes\UploadManager;
use App\Models\ContentDocument;
use App\Models\ContentVideo;
use App\Models\ContentVoice;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\CourseHeading;
use App\Models\CourseIntroduction;
use App\Models\Educator;
use App\Models\LevelOneGroup;
use App\Models\LevelThreeGroup;
use App\Models\LevelTwoGroup;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;


class CourseEditController extends BaseController
{
    public function editCourse(Request $request, $ep)
    {
        // check for maintenance balance
        if($request->input('user')->u_profile->m_balance < 0)
            return $this->sendResponse(Constant::$NEGETIVE_MAINTANANCE_BALANCE, null);

        $course = Course::find($request->input('course_id'));
        if (!$course) return $this->sendResponse(Constant::$COURSE_NOT_FOUND, null);

        $request->request->add(['course' => $course]);

        // Whenever edit content server should receive new hierarchy
        switch ($ep) {
            case Constant::$EDIT_PARAM_COMMENTS_AVAILABILITY:
                return $this->editCourseCommentsAvailability($request);
            case Constant::$EDIT_PARAM_COMMENTS_VALIDITY:
                return $this->editCourseCommentsValidity($request);;
            case Constant::$EDIT_PARAM_COVER:
                return $this->editCourseCover($request);
            case Constant::$EDIT_PARAM_DURATION:
                return $this->editCourseDuration($request);
            case Constant::$EDIT_PARAM_HOLDING_STATUS:
                return $this->editCourseHoldingStatus($request);
            case Constant::$EDIT_PARAM_LOGO:
                return $this->editCourseLogo($request);
            case Constant::$EDIT_PARAM_LONG_DESC:
                return $this->editCourseLongDesc($request);
            case Constant::$EDIT_PARAM_PRICE:
                return $this->editCoursePrice($request);
            case Constant::$EDIT_PARAM_RELEASE_DATE:
                return $this->editCourseReleaseDate($request);
            case Constant::$EDIT_PARAM_SHORT_DESC:
                return $this->editCourseShortDesc($request);
            case Constant::$EDIT_PARAM_TITLE:
                return $this->editCourseTitle($request);
            case Constant::$EDIT_PARAM_SUGGESTED_COURSES:
                return $this->editCourseSuggestedCourses($request);
            case Constant::$EDIT_PARAM_SUGGESTED_POSTS:
                return $this->editCourseSuggestedPosts($request);
            case Constant::$EDIT_PARAM_SUBJECTS:
                return $this->editCourseSubjects($request);
            case Constant::$EDIT_PARAM_REQUIREMENT:
                return $this->editCourseRequirements($request);
            case Constant::$EDIT_PARAM_GROUPS:
                return $this->editCourseGroups($request);
            case Constant::$EDIT_PARAM_TAGS:
                return $this->editCourseTags($request);
            case Constant::$EDIT_PARAM_CONTENT_VIDEO_ADD:
                return $this->addCourseContentVideo($request);
            case Constant::$EDIT_PARAM_CONTENT_VIDEO_UPDATE:
                return $this->updateCourseContentVideo($request);
            case Constant::$EDIT_PARAM_CONTENT_VIDEO_DELETE:
                return $this->deleteCourseContentVideo($request);
            case Constant::$EDIT_PARAM_CONTENT_VOICE_ADD:
                return $this->addCourseContentVoice($request);
            case Constant::$EDIT_PARAM_CONTENT_VOICE_UPDATE:
                return $this->updateCourseContentVoice($request);
            case Constant::$EDIT_PARAM_CONTENT_VOICE_DELETE:
                return $this->deleteCourseContentVoice($request);
            case Constant::$EDIT_PARAM_CONTENT_DOCUMENT_ADD:
                return $this->addCourseContentDocument($request);
            case Constant::$EDIT_PARAM_CONTENT_DOCUMENT_UPDATE:
                return $this->updateCourseContentDocument($request);
            case Constant::$EDIT_PARAM_CONTENT_DOCUMENT_DELETE:
                return $this->deleteCourseContentDocument($request);
            case Constant::$EDIT_PARAM_COURSE_INTRO_VIDEO_ADD:
                return $this->addCourseIntroVideo($request);
            case Constant::$EDIT_PARAM_COURSE_INTRO_VIDEO_UPDATE:
                return $this->updateCourseIntroVideo($request);
            case Constant::$EDIT_PARAM_COURSE_INTRO_VIDEO_DELETE:
                return $this->deleteCourseIntroVideo($request);
            case Constant::$EDIT_PARAM_COURSE_HEADING_ADD:
                return $this->addCourseHeading($request);
            case Constant::$EDIT_PARAM_COURSE_HEADING_UPDATE:
                return $this->updateCourseHeading($request);
            case Constant::$EDIT_PARAM_COURSE_HEADING_DELETE:
                return $this->deleteCourseHeading($request);
            case Constant::$EDIT_PARAM_CONTENT_HIERARCHY:
                return $this->editContentHierarchy($request);
            case Constant::$EDIT_PARAM_COURSE_EDUCATORS:
                return $this->editCourseEducators($request);
            default:
                return $this->sendResponse(Constant::$INVALID_EDIT_TYPE, null);
        }
    }

    public function editCourseLogo(Request $request)
    {
        $course = $request->input('course');
        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $course,
            "logo",
            false,
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function editCourseCover(Request $request)
    {
        $course = $request->input('course');

        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $course,
            "cover",
            false,
            false,
            $request->input('upload_key')
        );

        return $this->sendResponse($result, null);
    }

    public function editCourseTitle(Request $request)
    {
        $course = $request->input('course');
        $title = $request->input('title');

        if (!$title)
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $c = Course::where('title', $title)->first();
        if ($c && $c->id != $course->id)
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        $course->title = $title;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseShortDesc(Request $request)
    {
        $course = $request->input('course');
        $desc = $request->input('desc');

        if (!$desc) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course->short_desc = $desc;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseLongDesc(Request $request)
    {
        $course = $request->input('course');
        $desc = $request->input('desc');

        if (!$desc) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course->long_desc = $desc;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseReleaseDate(Request $request)
    {
        $course = $request->input('course');
        $date = $request->input('date'); // yyyy-mm-dd

        if (!preg_match("/\d{4}\-\d{2}-\d{2}/", $date))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $date = explode("-", $date);
        $course->release_date = Carbon::createFromDate($date[0], $date[1], $date[2]);;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseCommentsAvailability(Request $request)
    {
        $course = $request->input('course');

        $open = $request->input('open');
        if (!is_numeric($open)) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course->is_comments_open = $open;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseCommentsValidity(Request $request)
    {
        $course = $request->input('course');

        $valid = $request->input('valid');
        if (!is_numeric($valid)) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course->all_comments_valid = $valid;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseHoldingStatus(Request $request)
    {
        $course = $request->input('course');

        $status = $request->input('status');
        if (!$status) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course->holding_status = $status;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCoursePrice(Request $request)
    {
        $course = $request->input('course');
        $price = $request->input('price');

        if (!$price) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course->price = $price;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseDuration(Request $request)
    {
        $course = $request->input('course');
        $duration = $request->input('duration'); // minutes

        if (!$duration) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course->duration = $duration;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseDiscount(Request $request)
    {
        $course = $request->input('course');

        $has_discount = $request->input('has_discount');
        $discount = null;

        if ($has_discount) {
            $discount = (object)$request->input('discount');

            if (!isset($discount->value) || !isset($discount->type))
                return $this->sendResponse(Constant::$INVALID_VALUE, null);

            $discount = json_encode($discount);
        }

        $course->has_discount = $has_discount;
        $course->discount = $discount;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseSuggestedCourses(Request $request)
    {
        $course = $request->input('course');

        $ids = null;
        if (is_array($request->input('ids')) && sizeof($request->input('ids')) > 0) {
            $ids = $request->input('ids');
            $ids = json_encode($ids);
        }

        $course->suggested_courses = $ids;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseSuggestedPosts(Request $request)
    {
        $course = $request->input('course');

        $ids = null;
        if (is_array($request->input('ids')) && sizeof($request->input('ids')) > 0) {
            $ids = $request->input('ids');
            $ids = json_encode($ids);
        }

        $course->suggested_posts = $ids;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseSubjects(Request $request)
    {
        $course = $request->input('course');

        $subjects = null;
        if (is_array($request->input('subjects')) && sizeof($request->input('subjects')) > 0) {
            $subjects = $request->input('subjects');
            $subjects = json_encode($subjects);
        }

        $course->subjects = $subjects;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseRequirements(Request $request)
    {
        $course = $request->input('course');

        $requirements = null;
        if (is_array($request->input('requirements')) && sizeof($request->input('requirements')) > 0) {
            $requirements = $request->input('requirements');
            $requirements = json_encode($requirements);
        }

        $course->requirements = $requirements;
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseGroups(Request $request)
    {
        $course = $request->input('course');
        $groups = (object)$request->input('groups');

        if (!$groups)
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // check groups hierarchy
        if (!GroupsController::checkGroupsHierarchy($groups))
            return $this->sendResponse(Constant::$INVALID_GROUP_HIERARCHY, null);

        $course->level_one_group()->dissociate();
        $course->level_two_group()->dissociate();
        $course->level_three_group()->dissociate();
        $course->save();

        // add it to groups
        $g1 = LevelOneGroup::find($groups->g1);
        $g2 = LevelTwoGroup::find($groups->g2);
        $g3 = LevelThreeGroup::find($groups->g3);

        if ($g1) $g1->courses()->save($course);
        if ($g2) $g2->courses()->save($course);
        if ($g3) $g3->courses()->save($course);


        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editContentHierarchy(Request $request)
    {
        $course = $request->input('course');
        $hierarchy = (array)$request->input('hierarchy');

        if (!$hierarchy)
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        // TODO check it's validity

        $course->content_hierarchy = json_encode($hierarchy);
        $course->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseTags(Request $request)
    {
        $course = $request->input('course');
        $tags = (array)$request->input('tags');

        if (!$request->exists('tags'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course->tags()->detach();
        $course->save();

        foreach (Tag::find($tags) as $tag) $tag->courses()->save($course);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editCourseEducators(Request $request)
    {
        $course = $request->input('course');
        $educators = (array)$request->input('educators');

        if (!$request->exists('educators'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course->educators()->detach();

        foreach (Educator::find($educators) as $educator) $course->educators()->save($educator);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function addCourseContentVideo(Request $request)
    {
        $course = $request->input('course');

        if (
            !$request->exists('title') ||
            !$request->exists('is_free') ||
            !$request->exists('upload_key')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);


        $course_content = new CourseContent();
        $course_content->title = $request->input('title');
        $course_content->is_free = $request->input('is_free') ? 1 : 0;
        $course_content->type = Constant::$CONTENT_TYPE_VIDEO;

        $content_video = new ContentVideo();
        $content_video->belongs_to = Constant::$BELONGING_COURSE;
        $result = UploadManager::saveFile(
            tenant()->id,
            0,
            $content_video,
            'url',
            true,
            true,
            $request->input('upload_key')
        );

        if ($result == Constant::$SUCCESS) {
            $course->course_contents()->save($course_content);
            $course_content->content_video()->save($content_video);
        }

        return $this->sendResponse($result, ['content_id' => $course_content->id]);
    }

    public function updateCourseContentVideo(Request $request)
    {
        $course_content = CourseContent::find($request->input('content_id'));
        if (!$course_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

// amp change start
//         if (
//             !$request->exists('title') ||
//             !$request->exists('is_free') ||
//             !$request->exists('upload_key')
//         ) return $this->sendResponse(Constant::$INVALID_VALUE, null);
        
        if (
            !$request->exists('title') ||
            !$request->exists('is_free')
        ) return $this->sendResponse(Constant::$INVALID_VALUE, null);
// amp change end

        $course_content->title = $request->input('title');
        $course_content->is_free = $request->input('is_free') ? 1 : 0;

        $content_video = $course_content->content_video()->first();
        $result = UploadManager::updateFileState(
            $request->input('file_state'),
            tenant()->id,
            0,
            $content_video,
            "url",
            true,
            true,
            $request->input('upload_key')
        );

        if ($result == Constant::$SUCCESS) $course_content->save();

        return $this->sendResponse($result, null);
    }

    public function deleteCourseContentVideo(Request $request)
    {
        $course_content = CourseContent::find($request->input('content_id'));
        if (!$course_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $content_video = $course_content->content_video()->first();
        $result = UploadManager::deleteFile(tenant()->id, $content_video->url);

        if ($result == Constant::$SUCCESS) {
            $content_video->delete();
            $course_content->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addCourseContentVoice(Request $request)
    {
        $course = $request->input('course');

        if (
            !$request->exists('title') ||
            !$request->exists('is_free') ||
            !$request->exists('upload_key')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);


        $course_content = new CourseContent();
        $course_content->title = $request->input('title');
        $course_content->is_free = $request->input('is_free') ? 1 : 0;
        $course_content->type = Constant::$CONTENT_TYPE_VOICE;

        $content_voice = new ContentVoice();
        $content_voice->belongs_to = Constant::$BELONGING_COURSE;
        $result = UploadManager::saveFile(
            tenant()->id,
            0,
            $content_voice,
            'url',
            true,
            false,
            $request->input('upload_key')
        );

        if ($result == Constant::$SUCCESS) {
            $course->course_contents()->save($course_content);
            $course_content->content_voice()->save($content_voice);
        }

        return $this->sendResponse($result, ['content_id' => $course_content->id]);
    }

    public function updateCourseContentVoice(Request $request)
    {
        $course_content = CourseContent::find($request->input('content_id'));
        if (!$course_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (
            !$request->exists('title') ||
            !$request->exists('is_free') ||
            !$request->exists('upload_key')
        ) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course_content->title = $request->input('title');
        $course_content->is_free = $request->input('is_free') ? 1 : 0;

        $content_voice = $course_content->content_voice()->first();
        $result = UploadManager::updateFileState(
            $request->input('file_state'),
            tenant()->id,
            0,
            $content_voice,
            "url",
            true,
            false,
            $request->input('upload_key')
        );

        if ($result == Constant::$SUCCESS) $course_content->save();

        return $this->sendResponse($result, null);
    }

    public function deleteCourseContentVoice(Request $request)
    {
        $course_content = CourseContent::find($request->input('content_id'));
        if (!$course_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $content_voice = $course_content->content_voice()->first();
        $result = UploadManager::deleteFile(tenant()->id, $content_voice->url);

        if ($result == Constant::$SUCCESS) {
            $content_voice->delete();
            $course_content->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addCourseContentDocument(Request $request)
    {
        $course = $request->input('course');

        if (
            !$request->exists('title') ||
            !$request->exists('is_free') ||
            !$request->exists('upload_key')
        )
            return $this->sendResponse(Constant::$INVALID_VALUE, null);


        $course_content = new CourseContent();
        $course_content->title = $request->input('title');
        $course_content->is_free = $request->input('is_free') ? 1 : 0;
        $course_content->type = Constant::$CONTENT_TYPE_DOCUMENT;

        $content_document = new ContentDocument();
        $content_document->belongs_to = Constant::$BELONGING_COURSE;
        $result = UploadManager::saveFile(
            tenant()->id,
            0,
            $content_document,
            'url',
            true,
            false,
            $request->input('upload_key')
        );

        if ($result == Constant::$SUCCESS) {
            $course->course_contents()->save($course_content);
            $course_content->content_document()->save($content_document);
        }

        return $this->sendResponse($result, ['content_id' => $course_content->id]);
    }

    public function updateCourseContentDocument(Request $request)
    {
        $course_content = CourseContent::find($request->input('content_id'));
        if (!$course_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (
            !$request->exists('title') ||
            !$request->exists('is_free') ||
            !$request->exists('upload_key')
        ) return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $course_content->title = $request->input('title');
        $course_content->is_free = $request->input('is_free') ? 1 : 0;

        $content_document = $course_content->content_document()->first();
        $result = UploadManager::updateFileState(
            $request->input('file_state'),
            tenant()->id,
            0,
            $content_document,
            "url",
            true,
            false,
            $request->input('upload_key')
        );

        if ($result == Constant::$SUCCESS) $course_content->save();

        return $this->sendResponse($result, null);
    }

    public function deleteCourseContentDocument(Request $request)
    {
        $course_content = CourseContent::find($request->input('content_id'));
        if (!$course_content) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $content_document = $course_content->content_document()->first();
        $result = UploadManager::deleteFile(tenant()->id, $content_document->url);

        if ($result == Constant::$SUCCESS) {
            $content_document->delete();
            $course_content->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addCourseIntroVideo(Request $request)
    {
        $course = $request->input('course');

        if (!$request->exists('upload_key'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $intro = new CourseIntroduction();
        $content_video = new ContentVideo();
        $content_video->belongs_to = Constant::$BELONGING_COURSE;

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
            $course->course_introduction()->save($intro);
            $intro->content_video()->save($content_video);
        }

        return $this->sendResponse($result, ['course_introduction_id' => $intro->id]);
    }

    public function updateCourseIntroVideo(Request $request)
    {
        $course_introduction = CourseIntroduction::find($request->input('intro_id'));
        if(!$course_introduction) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (!$request->exists('upload_key'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $content_video = $course_introduction->content_video()->first();
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

    public function deleteCourseIntroVideo(Request $request)
    {
        $course_introduction = CourseIntroduction::find($request->input('intro_id'));
        if(!$course_introduction) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $content_video = $course_introduction->content_video()->first();
        $result = UploadManager::deleteFile(tenant()->id, $content_video->url);

        if ($result == Constant::$SUCCESS) {
            $content_video->delete();
            $course_introduction->delete();
        }

        return $this->sendResponse($result, null);
    }

    public function addCourseHeading(Request $request)
    {
        $course = $request->input('course');

        if (!$request->exists('title'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $heading = new CourseHeading();
        $heading->title = $request->input('title');
        $course->course_headings()->save($heading);

        return $this->sendResponse(Constant::$SUCCESS,  ['heading_id' => $heading->id]);
    }

    public function updateCourseHeading(Request $request)
    {
        $heading = CourseHeading::find($request->input('heading_id'));
        if(!$heading) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (!$request->exists('title'))
            return $this->sendResponse(Constant::$INVALID_VALUE, null);

        $heading->title = $request->input('title');
        $heading->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteCourseHeading(Request $request)
    {
        $heading = CourseHeading::find($request->input('heading_id'));
        if(!$heading) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        $heading->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }
}
