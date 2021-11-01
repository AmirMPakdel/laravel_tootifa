<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Course;
use App\Models\UploadTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadController extends BaseController
{
    public function verifyForDownloadCourseItem(Request $request)
    {
        $course = Course::find($request->input('course_id'));
        if(!$course)return $this->sendResponse(Constant::$COURSE_NOT_FOUND, null);

        $student = $request->input('student');
        $upload_transaction = UploadTransaction::where('upload_key', $request->input('upload_key'))->first();

        $free_types = [
            Constant::$UPLOAD_TYPE_COURSE_DOCUMENT_FREE,
            Constant::$UPLOAD_TYPE_COURSE_VIDEO_FREE,
            Constant::$UPLOAD_TYPE_COURSE_VOICE_FREE
        ];

        if (!in_array($upload_transaction->upload_type, $free_types)) {
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

        return $this->sendResponse(Constant::$SUCCESS, null);
    }
}
