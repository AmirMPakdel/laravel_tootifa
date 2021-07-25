<?php


namespace App\Http\Controllers\API\Student;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends BaseController
{
    public function loadStudentProfile(Request $request){
        $student = $request->input('student');

        $result = [
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'email' => $student->email,
            'national_code' => $student->national_code,
            'phone_number' => $student->phone_number,
            'state' => $student->state,
            'city' => $student->city
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }

    public function updateStudentProfile(Request $request){
        $student = $request->input('student');

        if ($student->email != $request->input('email'))
            $student->email_verified_at = null;

        $student->first_name = $request->input('first_name');
        $student->last_name = $request->input('last_name');
        $student->email = $request->input('email');
        $student->national_code = $request->input('national_code');
        $student->state = $request->input('state');
        $student->city = $request->input('city');
        $student->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }


}
