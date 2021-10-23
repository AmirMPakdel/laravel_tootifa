<?php

namespace App\Http\Controllers\API\Student\App;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\Student\StudentCourseController;
use App\Includes\Constant;
use App\Models\Course;
use App\Models\LicenseKey;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class CoursesController extends BaseController
{
    public function registerCourseInDevice(Request $request){
        $lk = $request->input('lk');
        $deviceInfo = json_decode($request->input('device_info'));
        $tenant = Tenant::find(User::where('key', substr($lk, 0, 4))->first()->tenant_id);
        
        $user_info = [
            'user_id' => $tenant->id,
        ];

        $result = $tenant->run(function() use ($lk, $deviceInfo, $user_info){
            $licenseKey = LicenseKey::where('key', $lk)->first();
            if($licenseKey == null) return $this->sendResponse(Constant::$LISCENSE_KEY_NOT_FOUND, null);

            $scc = new StudentCourseController();
            $course = $scc->buildCourseObject(
                Student::find($licenseKey->student_id),
                Course::find($licenseKey->course_id)
            );

            $content = [
                'user_info' => $user_info,
                'course' => $course
            ];

            $d1 = json_decode($licenseKey->device_one);
            $d2 = json_decode($licenseKey->device_two);

            if($d1 && $d2){
                if($d1->imei == $deviceInfo->imei)
                    return $this->sendResponse(Constant::$SUCCESS, $content);

                if($d2->imei == $deviceInfo->imei)
                    return $this->sendResponse(Constant::$SUCCESS, $content);

                return $this->sendResponse(Constant::$DEVICE_LIMIT, null);
            }

            if(!$d1 && !$d2){
                $licenseKey->device_one = json_encode($deviceInfo);
                $licenseKey->save();
                return $this->sendResponse(Constant::$SUCCESS, $content);
            }

            if($d1 && !$d2){
                if($d1->imei == $deviceInfo->imei)
                    return $this->sendResponse(Constant::$SUCCESS, $content);

                $licenseKey->device_two = json_encode($deviceInfo);
                $licenseKey->save();
                return $this->sendResponse(Constant::$SUCCESS, $content);
            }

            if(!$d1 && $d2){
                if($d2->imei == $deviceInfo->imei)
                    return $this->sendResponse(Constant::$SUCCESS, $content);

                $licenseKey->device_one = json_encode($deviceInfo);
                $licenseKey->save();
                return $this->sendResponse(Constant::$SUCCESS, $content);
            }
        });

        return $result;
    }

    public function loadCourses(Request $request){
        $imei = $request->input('imei');
        $keys = json_decode($request->input('keys'));
        $courses = [];

        foreach($keys as $key){
            $tenant = Tenant::find($key->user_id);
            if($tenant == null) array_push($courses, null);

            $course = $tenant->run(function() use ($key, $imei){
                $licenseKey = LicenseKey::where('key', $key->lk)->first();
                if($licenseKey == null) return null;

                $scc = new StudentCourseController();
                $course = $scc->buildCourseObject(
                    Student::find($licenseKey->student_id),
                    Course::find($licenseKey->course_id)
                );

                $d1 = json_decode($licenseKey->device_one);
                $d2 = json_decode($licenseKey->device_two);

                if($d1 != null && $d1->imei == $imei){
                    return $course;
                }

                if($d2 != null && $d2->imei == $imei){
                    return $course;
                }

                return null;
            });

            array_push($courses, $course);
        }

        return $this->sendResponse(Constant::$SUCCESS, $courses);
    }
}
