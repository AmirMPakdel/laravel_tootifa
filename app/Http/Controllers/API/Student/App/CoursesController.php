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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursesController extends BaseController
{
    public function getLicenseKeyShollowInfo(Request $request){
        $lk = $request->input('lk');
        $tenant = Tenant::find(User::where('key', substr($lk, 0, 4))->first()->tenant_id);
        
        if(!$tenant) return $this->sendResponse(Constant::$USER_NOT_FOUND, null);

        $username = $tenant->id;
        $result = $tenant->run(function() use ($lk, $username){
            $licenseKey = LicenseKey::where('key', $lk)->first();
            if($licenseKey == null) return $this->sendResponse(Constant::$LISCENSE_KEY_NOT_FOUND, null);

            $content = [
                'username' => $username,
                'course_id' => $licenseKey->course_id
            ];

            return $this->sendResponse(Constant::$SUCCESS, $content);
        });

        return $result;
    }

    public function registerCourseInDevice(Request $request){
        $lk = $request->input('lk');
        $old_lk = $request->input('old_lk');
        $deviceInfo = (object)$request->input('device_info');
        $tenant = Tenant::find(User::where('key', substr($lk, 0, 4))->first()->tenant_id);

        if(!$tenant) return $this->sendResponse(Constant::$USER_NOT_FOUND, null);

        $user_info = [
            'username' => $tenant->id,
            'domain' => 'foo',
            'title' => 'bar',
        ];

        $result = $tenant->run(function() use ($lk, $old_lk, $deviceInfo, $user_info){
            $licenseKey = LicenseKey::where('key', $lk)->first();
            if($licenseKey == null) return $this->sendResponse(Constant::$LISCENSE_KEY_NOT_FOUND, null);

            $old_licenseKey = null;
            if($old_lk != null){
                $old_licenseKey = LicenseKey::where('key', $old_lk)->first();
                if($old_licenseKey == null) return $this->sendResponse(Constant::$OLD_LISCENSE_KEY_NOT_FOUND, null);
            }

            $complete_course = Course::find($licenseKey->course_id);
            if($complete_course->validation_status != Constant::$VALIDATION_STATUS_VALID)
                return $this->sendResponse(Constant::$COURSE_NOT_VALID, null);

            $course = $this->buildCourseObject(
                Student::find($licenseKey->student_id),
                $complete_course,
                $user_info['username']
            );

            $content = [
                'user_info' => $user_info,
                'student_id' => $licenseKey->student_id,
                'course' => $course
            ];

            $d1 = (object)json_decode($licenseKey->device_one, true);
            $d2 = (object)json_decode($licenseKey->device_two, true);

            if($d1 && $d2){

                if($d1->imei != $deviceInfo->imei && $d2->imei != $deviceInfo->imei)
                   return $this->sendResponse(Constant::$DEVICE_LIMIT, null);

            }elseif(!$d1 && !$d2){

                $licenseKey->device_one = json_encode($deviceInfo);
                $licenseKey->save();

            }elseif($d1 && !$d2){

                if($d1->imei != $deviceInfo->imei){
                    $licenseKey->device_two = json_encode($deviceInfo);
                    $licenseKey->save();
                }
        
            }elseif(!$d1 && $d2){

                if($d2->imei != $deviceInfo->imei){
                    $licenseKey->device_one = json_encode($deviceInfo);
                    $licenseKey->save();
                }

            }

            // to prevent two different lk's connected to one course in one device
            if($old_licenseKey != null && $old_licenseKey != $licenseKey){
                $d1 = (object)json_decode($licenseKey->device_one, true);
                $d2 = (object)json_decode($licenseKey->device_two, true);

                if($d1->imei == $deviceInfo->imei)
                    $old_licenseKey->device_one = null;
                elseif($d2->imei == $deviceInfo->imei)
                    $old_licenseKey->device_two = null;

                $old_licenseKey->save();
            }

            return $this->sendResponse(Constant::$SUCCESS, $content);
        });

        return $result;
    }

    public function loadCourses(Request $request){
        $imei = $request->input('imei');
        $keys = json_decode($request->input('keys'));
        $courses = [];

        foreach($keys as $key){
            $tenant = Tenant::find($key->username);
            if($tenant == null) {
                array_push($courses, Constant::$USER_NOT_FOUND);
                continue;
            }

            $course = $tenant->run(function() use ($key, $imei){
                $licenseKey = LicenseKey::where('key', $key->lk)->first();
                if($licenseKey == null) return Constant::$LISCENSE_KEY_NOT_FOUND;

                $complete_course = Course::find($licenseKey->course_id);
                if($complete_course->validation_status != Constant::$VALIDATION_STATUS_VALID)
                    return $this->sendResponse(Constant::$COURSE_NOT_VALID, null);

                $course = $this->buildCourseObject(
                    Student::find($licenseKey->student_id),
                    $complete_course,
                    $key->username
                );

                $d1 = json_decode($licenseKey->device_one);
                $d2 = json_decode($licenseKey->device_two);

                if($d1 != null && $d1->imei == $imei){
                    return $course;
                }

                if($d2 != null && $d2->imei == $imei){
                    return $course;
                }

                return Constant::$DEVICE_NOT_FOUND;
            });

            array_push($courses, $course);
        }

        return $this->sendResponse(Constant::$SUCCESS, $courses);
    }


    public function buildCourseObject($student, $course, $username){
        $has_access = DB::table('course_student')
                ->whereCourseId($course->id)
                ->whereStudentId($student->id)
                ->whereAccess(1)
                ->count() > 0;

        $headings = $course->course_headings()->get()->map(function ($heading){
            return ['id' => $heading->id, 'title' => $heading->title];
        });

        $contents = $course->course_contents()->get()->map(function ($content) use ($has_access){
            $c = [
                'id' => $content->id,
                'title' => $content->title,
                'type' => $content->type,
                'is_free' => $content->is_free,
            ];

            switch ($content->type) {
                case Constant::$CONTENT_TYPE_VIDEO:
                    $c['url'] = ($has_access || $content->is_free) ? $content->content_video->url : null;
                    $c['size'] = $content->content_video->size;
                    $c['encoding'] = $content->content_video->encoding;
                    break;
                case Constant::$CONTENT_TYPE_VOICE:
                    $c['url'] = ($has_access || $content->is_free) ? $content->content_voice->url : null;
                    $c['size'] = $content->content_voice->size;
                    break;
                case Constant::$CONTENT_TYPE_DOCUMENT:
                    $c['url'] = ($has_access || $content->is_free) ? $content->content_document->url : null;
                    $c['size'] = $content->content_document->size;
            }

            return $c;
        });

        $logo = null;
        if($course->logo) {
            $logo_file_type = UploadTransaction::where('upload_key', $course->logo)->first()->file_type;
            $logo = Helper::generatePublicDownloadFileUrl($username, $course->logo, $logo_file_type);
        }

        return [
            'id' => $course->id,
            'has_access' => $has_access,
            'title' => $course->title,
            "is_encrypted" => $course->is_encrypted,
            "headings" => $headings,
            "contents" => $contents,
            "content_hierarchy" => json_decode($course->content_hierarchy),
            "logo" => $logo,
        ];
    }

    
}
