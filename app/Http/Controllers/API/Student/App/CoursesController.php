<?php

namespace App\Http\Controllers\API\Student\App;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\Helper;
use App\Models\Course;
use App\Models\LicenseKey;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\UploadTransaction;
use App\Models\UProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CoursesController extends BaseController
{
    public function registerCourseInDevice(Request $request)
    {
        $lk = $request->input('lk');
        $deviceInfo = $request->input('device_info');
        // device info consists of:
        // uid, platform (android or windows), platform_version, app_version
        $user = User::where('key', substr($lk, 0, 4))->first();
        if(!$user) return $this->sendResponse(Constant::$LISCENSE_KEY_NOT_FOUND, null);
        $tenant = Tenant::find($user->tenant_id);

        if (!$tenant) return $this->sendResponse(Constant::$USER_NOT_FOUND, null);

        $profile = UProfile::where('tenant_id', $tenant->id)->first();
        $user_info = [
            'username' => $tenant->id,
            'domain' => $profile->domain,
            'title' => $profile->title,
        ];

        $result = $tenant->run(function () use ($lk, $deviceInfo, $user_info) {
            $licenseKey = LicenseKey::where('key', $lk)->first();
            if ($licenseKey == null) return $this->sendResponse(Constant::$LISCENSE_KEY_NOT_FOUND, null);

            $complete_course = Course::find($licenseKey->course_id);
            if ($complete_course->validation_status != Constant::$VALIDATION_STATUS_VALID)
                return $this->sendResponse(Constant::$COURSE_NOT_VALID, null);

            $course = $this->buildCourseObject(
                Student::find($licenseKey->student_id),
                $complete_course,
                $user_info['username'],
                $lk
            );

            $content = [
                'user_info' => $user_info,
                'student_id' => $licenseKey->student_id,
                'course' => $course
            ];

            $d1 = json_decode($licenseKey->device_one, true);
            $d2 = json_decode($licenseKey->device_two, true);

            if ($d1 && $d2) {

                if ($d1['uid'] != $deviceInfo['uid'] && $d2['uid'] != $deviceInfo['uid'])
                    return $this->sendResponse(Constant::$DEVICE_LIMIT, null);
            } elseif (!$d1 && !$d2) {

                $licenseKey->device_one = json_encode($deviceInfo);
                $licenseKey->save();
            } elseif ($d1 && !$d2) {

                if ($d1['uid'] != $deviceInfo['uid']) {
                    $licenseKey->device_two = json_encode($deviceInfo);
                    $licenseKey->save();
                }
            } elseif (!$d1 && $d2) {

                if ($d2['uid'] != $deviceInfo['uid']) {
                    $licenseKey->device_one = json_encode($deviceInfo);
                    $licenseKey->save();
                }
            }

            // to prevent two different lk's connected to one course in one device
            $old_lk_d1 = LicenseKey::where([
                ['device_one->uid',  $deviceInfo['uid']],
                ['key', '<>', $licenseKey->key],
                ['course_id', $licenseKey->course_id],
            ])->first();

            if ($old_lk_d1) {
                $old_lk_d1->device_one = null;
                $old_lk_d1->save();
            }

            $old_lk_d2 = LicenseKey::where([
                ['device_two->uid',  $deviceInfo['uid']],
                ['key', '<>', $licenseKey->key],
                ['course_id', $licenseKey->course_id],
            ])->first();

            if ($old_lk_d2) {
                $old_lk_d2->device_two = null;
                $old_lk_d2->save();
            }

            return $this->sendResponse(Constant::$SUCCESS, $content);
        });

        return $result;
    }

    public function loadCourses(Request $request)
    {
        $uid = $request->input('uid');
        $keys = $request->input('keys');
        $courses = [];

        foreach ($keys as $key) {
            $key = (object)$key;
            $tenant = Tenant::find($key->username);
            if ($tenant == null) {
                array_push($courses, Constant::$USER_NOT_FOUND);
                continue;
            }

            $course = $tenant->run(function () use ($key, $uid) {
                $licenseKey = LicenseKey::where('key', $key->lk)->first();
                if ($licenseKey == null) return Constant::$LISCENSE_KEY_NOT_FOUND;

                $complete_course = Course::find($licenseKey->course_id);
                if ($complete_course->validation_status != Constant::$VALIDATION_STATUS_VALID)
                    return Constant::$COURSE_NOT_VALID;

                $course = $this->buildCourseObject(
                    Student::find($licenseKey->student_id),
                    $complete_course,
                    $key->username,
                    $key->lk
                );

                $d1 = json_decode($licenseKey->device_one, true);
                $d2 = json_decode($licenseKey->device_two, true);

                if ($d1 != null && $d1['uid'] == $uid) {
                    return $course;
                }

                if ($d2 != null && $d2['uid'] == $uid) {
                    return $course;
                }

                return Constant::$DEVICE_NOT_FOUND;
            });

            array_push($courses, $course);
        }

        return $this->sendResponse(Constant::$SUCCESS, $courses);
    }

    public function loadCourse(Request $request)
    {
        $uid = $request->input('uid');
        $lk = $request->input('lk');
        $username = $request->input('username');

        $tenant = Tenant::find($username);
        if ($tenant == null) return $this->sendResponse(Constant::$USER_NOT_FOUND, null);

        $result = $tenant->run(function () use ($lk, $username, $uid) {
            $licenseKey = LicenseKey::where('key', $lk)->first();
            if ($licenseKey == null) return $this->sendResponse(Constant::$LISCENSE_KEY_NOT_FOUND, null);

            $complete_course = Course::find($licenseKey->course_id);
            if ($complete_course->validation_status != Constant::$VALIDATION_STATUS_VALID)
                return $this->sendResponse(Constant::$COURSE_NOT_VALID, null);

            $course = $this->buildCourseObject(
                Student::find($licenseKey->student_id),
                $complete_course,
                $username,
                $lk
            );

            $d1 = json_decode($licenseKey->device_one, true);
            $d2 = json_decode($licenseKey->device_two, true);

            if ( ($d1 != null && $d1['uid'] == $uid) || ($d2 != null && $d2['uid'] == $uid) ) 
                return $this->sendResponse(Constant::$SUCCESS, $course);
            
            return $this->sendResponse(Constant::$DEVICE_NOT_FOUND, $course);
        });

        return $result;
    }

    public function resetUserLks(Request $request)
    {
        $keys = $request->input('keys');
        $results = [];

        foreach ($keys as $key) {
            $key = (object)$key;
            $tenant = Tenant::find($key->username);

            if ($tenant == null) {
                array_push($results, "NA");
                continue;
            }
    
            $result = $tenant->run(function () use ($key) {
                $licenseKey = LicenseKey::where('key', $key->lk)->first();
                if ($licenseKey == null) return "NA";

                $licenseKey->device_one = null;
                $licenseKey->device_two = null;
                $licenseKey->save();

                return "OK";
            });

            array_push($results, $result);
        }

        return $this->sendResponse(Constant::$SUCCESS, $results);
    }

    public function buildCourseObject($student, $course, $username, $lk)
    {
        $has_access = DB::table('course_student')
            ->whereCourseId($course->id)
            ->whereStudentId($student->id)
            ->whereAccess(1)
            ->count() > 0;

        $headings = $course->course_headings()->get()->map(function ($heading) {
            return ['id' => $heading->id, 'title' => $heading->title];
        });

        $educators = $course->educators()->get()->map(function ($educator){
            return [
                'id' => $educator->id,
                'first_name' => $educator->first_name,
                'last_name' => $educator->last_name,
                'bio' => $educator->bio,
                'image' => $educator->image,
            ];
        });

        $contents = $course->course_contents()->get()->map(function ($content) use ($has_access, $lk, $course, $username) {
            $c = [
                'id' => $content->id,
                'title' => $content->title,
                'type' => $content->type,
                'is_free' => $content->is_free,
            ];
            
            switch ($content->type) {
                case Constant::$CONTENT_TYPE_VIDEO:
                    $ut = UploadTransaction::where("upload_key", $content->content_video->url)->first();
                    $c['url'] = ($has_access || $content->is_free) ? $content->content_video->url : null;
                    $c['size'] = $content->content_video->size;
                    $c['encoding'] = $content->content_video->encoding;
                    $c['enc_key'] = ($content->content_video->encoding) ? $ut->enc_key : null;
                    $c['iv'] = ($content->content_video->encoding) ? Constant::$IV : null;
                    $c['time'] = null;
                    break;
                case Constant::$CONTENT_TYPE_VOICE:
                    $c['url'] = ($has_access || $content->is_free) ? $content->content_voice->url : null;
                    $c['size'] = $content->content_voice->size;
                    $c['time'] = null;
                    break;
                case Constant::$CONTENT_TYPE_DOCUMENT:
                    $c['url'] = ($has_access || $content->is_free) ? $content->content_document->url : null;
                    $c['size'] = $content->content_document->size;
            }

            if ($c['url'] != null) {
                $c['url'] = Helper::generateStudentDownloadCourseItemFileUrl2(
                    $username,
                    $c['url'],
                    $course->id,
                    $content->id,
                    null,
                    $lk
                );
            }

            return $c;
        });

        $logo = null;
        if ($course->logo) {
            $logo_file_type = UploadTransaction::where('upload_key', $course->logo)->first()->file_type;
            $logo = Helper::generatePublicDownloadFileUrl($username, $course->logo, $logo_file_type);
        }

        return [
            'id' => $course->id,
            'has_access' => $has_access,
            'title' => $course->title,
            "is_encrypted" => $course->is_encrypted,
            "is_online" => $course->is_online,
            "headings" => $headings,
            "contents" => $contents,
            "content_hierarchy" => json_decode($course->content_hierarchy),
            "educators" => $educators,
            "logo" => $logo,
        ];
    }

}
