<?php
namespace App\Http\Controllers\API\Admin\Courses;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\API\Admin\Courses\CoursesController;
use App\Includes\Constant;

class CourseStudentsImport implements ToCollection
{
    private $course;

    public function __construct($course) {
        $this->course = $course;
    }

    public function collection($rows)
    {
        foreach ($rows as $row)
        {
            $student = Student::where('national_code', $row[0])->first();
            if ($student){
                $cc = new CoursesController();
                $cc->addStudentToCourse($student, $this->course, Constant::$REGISTRATION_TYPE_CUSTOM);
            }
        }
    }
}
