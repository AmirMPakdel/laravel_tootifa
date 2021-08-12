<?php

namespace App\Http\Controllers\API\Admin\Courses;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\Admin\Courses\CoursesController;
use App\Includes\Constant;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class CourseStudentController extends BaseController
{
    public function fetchCourseStudents(Request $request){
        $course = Course::find($request->input('course_id'));
        $students = $course->students->map(function ($student){
            return [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'phone_number' => $student->phone_number,
                'national_code' => $student->national_code,
                'access' => $student->pivot->access
            ];
        });

        return $this->sendResponse(Constant::$SUCCESS, $students);
    }

    public function importCourseStudentsExcel(Request $request){
        $course = Course::find($request->input('course_id'));

        $path1 =  $request->file('file')->store('temp');
        $path=storage_path('app').'/'.$path1;

        Excel::import(
            new CourseStudentsImport($course),
            $path
        );

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function exportCourseStudentsExcel(Request $request){
        $course = Course::find($request->input('course_id'));

        $export = new CourseStudentsExport($course);
        return Excel::download($export, "لیست دانش آموزان دوره {$course->title}.xlsx");
    }

    public function addCourseStudent(Request $request){
        $course = Course::find($request->input('course_id'));
        $student = Student::find($request->input('student_id'));

        $cc = new CoursesController();
        $cc->addStudentToCourse($student, $course);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function removeCourseStudents(Request $request){
        $course = Course::find($request->input('course_id'));
        $students = Student::find($request->input('student_ids'));

        $cc = new CoursesController();
        foreach ($students as $student)
            $cc->removeStudentFromCourse($student, $course);

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function changeCourseStudentsAccess(Request $request){
        $course = Course::find($request->input('course_id'));
        $students = Student::find($request->input('student_ids'));

        $cc = new CoursesController();
        foreach ($students as $student)
            $cc->setStudentCourseAccess($student, $course, $request->input('access'));

        return $this->sendResponse(Constant::$SUCCESS, null);
    }
}







