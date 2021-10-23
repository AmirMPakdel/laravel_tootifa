<?php

namespace App\Http\Controllers\API\Admin\Courses;

use App\Includes\Constant;
use Maatwebsite\Excel\Concerns\FromArray;

class CourseStudentsExport implements FromArray
{
    private $course;

    public function __construct($course = null)
    {
        $this->course = $course;
    }

    public function array(): array
    {
        $headers = [
            'نام',
            'نام خانوادگی',
            'کد ملی',
            'شماره تلفن همراه',
            'استان',
            'شهر',
            'ایمیل',
            'دسترسی',
            'نوع ثبت نام'
        ];

        $data = [$headers];

        foreach ($this->course->students as $student) {
            $item = [
                $student->first_name,
                $student->last_name,
                $student->national_code,
                $student->phone_number,
                $student->state,
                $student->city,
                $student->email,
                $student->pivot->access ? Constant::$STR_HAS_ACCESS : Constant::$STR_HAS_NOT_ACCESS,
                ($student->pivot->registration_type == Constant::$REGISTRATION_TYPE_WEBSITE) ? Constant::$STR_WEBSITE : Constant::$STR_CUSTOM,
            ];

            array_push($data, $item);
        }

        return $data;
    }
}
