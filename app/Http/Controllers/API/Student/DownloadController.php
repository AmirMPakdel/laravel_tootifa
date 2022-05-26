<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\LicenseKey;
use App\Models\Student;
use App\Models\UploadTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadController extends BaseController
{
    public function verifyStudentForDownloadCourseItem(Request $request)
    {
        $course = Course::find($request->input('course_id'));
        if (!$course) return $this->sendResponse(Constant::$COURSE_NOT_FOUND, null);

        $student = Student::find($request->input('student_id'));
        if (!$student) return $this->sendResponse(Constant::$STUDENT_NOT_FOUND, null);

        $upload_transaction = UploadTransaction::where('upload_key', $request->input('upload_key'))->first();
        if (!$upload_transaction) return $this->sendResponse(Constant::$INVALID_UPLOAD_KEY, null);

        $cc = CourseContent::find($request->input('content_id'));
        if (!$cc) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        // TODO check the relation between inputs
        
        // if (!in_array($upload_transaction->upload_type, Constant::getCourseFreeUploadTypes())) {}
        if (!$cc->is_free) {
            $registered = DB::table('course_student')
                ->whereCourseId($course->id)
                ->whereStudentId($student->id)
                ->count() > 0;

            $has_access = DB::table('course_student')
                ->whereCourseId($course->id)
                ->whereStudentId($student->id)
                ->whereAccess(1)
                ->count() > 0;

            if (!$registered)
                return $this->sendResponse(Constant::$NOT_REGISTERED_IN_COURSE, null);

            if (!$has_access)
                return $this->sendResponse(Constant::$NO_ACCESS_TO_COURSE, null);
        }

        $result = [
            'file_type' => $upload_transaction->file_type
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function verifyStudentForDownloadCourseItem2(Request $request)
    {
        $upload_transaction = UploadTransaction::where('upload_key', $request->input('upload_key'))->first();
            if (!$upload_transaction) return $this->sendResponse(Constant::$INVALID_UPLOAD_KEY, null);

        $cc = CourseContent::find($request->input('content_id'));
        if (!$cc) return $this->sendResponse(Constant::$CONTENT_NOT_FOUND, null);

        if (!$cc->is_free) {
            $token = $request->input('token');
            $lk = $request->input('lk');
            $student = null;

            if($token){
                $student = Student::where('token', $token)->first();
                if(!$student){
                    return $this->sendResponse(Constant::$INVALID_TOKEN, null);
                }
            }else if($lk){
                $license_key = LicenseKey::where('key', $lk)->first();
                if (!$license_key){
                    return $this->sendResponse(Constant::$INVALID_LIECENSE_KEY, null);
                }
                $student = Student::find($license_key->student_id);
                if(!$student){
                    return $this->sendResponse(Constant::$INVALID_LIECENSE_KEY, null);
                }
            }

            $course = Course::find($request->input('course_id'));
            if (!$course) return $this->sendResponse(Constant::$COURSE_NOT_FOUND, null);

            // TODO check the relation between inputs
            
            $registered = DB::table('course_student')
                    ->whereCourseId($course->id)
                    ->whereStudentId($student->id)
                    ->count() > 0;

                $has_access = DB::table('course_student')
                    ->whereCourseId($course->id)
                    ->whereStudentId($student->id)
                    ->whereAccess(1)
                    ->count() > 0;

                if (!$registered)
                    return $this->sendResponse(Constant::$NOT_REGISTERED_IN_COURSE, null);

                if (!$has_access)
                    return $this->sendResponse(Constant::$NO_ACCESS_TO_COURSE, null);
        }

        $result = [
            'file_type' => $upload_transaction->file_type
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }


    public function getStuednt(Request $request){
        $token = $request->input('token');
        $lk = $request->input('lk');
        $student = null;

        if($token){
            $student = Student::where('token', $token)->first();
            if(!$student){
                return $this->sendResponse(Constant::$INVALID_TOKEN, null);
            }
        }else if($lk){
            $license_key = LicenseKey::where('key', $lk)->first();
            if (!$license_key){
                return $this->sendResponse(Constant::$INVALID_LIECENSE_KEY, null);
            }
            $student = Student::find($license_key->student_id);
            if(!$student){
                return $this->sendResponse(Constant::$INVALID_LIECENSE_KEY, null);
            }
        }

        $result = [
            'id' => $student->id,
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }
}
