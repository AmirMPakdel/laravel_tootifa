<?php


namespace App\Http\Controllers\API\Student;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentRegistrationController extends BaseController
{
    public function checkPhoneNumber(Request $request){
        $phone_number = $request->input('phone_number');

        $student = Student::where([
            ['phone_number', $phone_number],
            ['phone_verified_at', '<>', null]
        ])->exists();

        if ($student)
            return $this->sendResponse(Constant::$REPETITIVE_PHONE_NUMBER, null);
        else
            return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function loginWithPassword(Request $request){
        $phone_number = $request->input('phone_number');
        $password = $request->input('password');

        $student = Student::where([
            ['phone_number', $phone_number],
            ['phone_verified_at', '<>', null]
        ])->first();

        if (!$student)
            return $this->sendResponse(Constant::$INVALID_PHONE_NUMBER, null);

        if (!Hash::check($password, $student->password))
            return $this->sendResponse(Constant::$INVALID_PASSWORD, null);

        $student->token = bin2hex(random_bytes(16));
        $student->save();

        return $this->sendResponse(Constant::$SUCCESS, ['token' => $student->token]);
    }

    public function sendVerificationCode(Request $request){
        $phone_number = $request->input('phone_number');

        // check for verification status
        $student = Student::where([
            ['phone_number', $phone_number],
            ['phone_verified_at', '<>', null]
        ])->first();

        if($student) return $this->sendResponse(Constant::$USER_ALREADY_VERIFIED, null);

        // check for in process student
        $student = Student::where([
            ['phone_number', $phone_number],
            ['phone_verified_at', null]
        ])->first();

        // create student if not exist
        if (!$student) {
            $student = new Student();
            $student->phone_number = $phone_number;
        }

        //generate and send verification code
        $code = 1111;
        // $code = mt_rand(1000, 9999);
        $student->verification_code = $code;
        $student->save();

        // TODO send verification code via third party sms platform api

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function checkVerificationCode(Request $request){
        $student = Student::where([
            ['verification_code', $request->input('code')],
            ['phone_number', $request->input('phone_number')]
        ])->first();

        if ($student)
            return $this->sendResponse(Constant::$SUCCESS, ['student_id' => $student->id]);
        else
            return $this->sendResponse(Constant::$INVALID_VERIFICATION_CODE, null);
    }

    public function completeRegistration(Request $request){
        // check national code
        $national_code = $request->input('national_code');

        if (Student::where('national_code', $national_code)->exists())
            return $this->sendResponse(Constant::$REPETITIVE_NATIONAL_CODE, null);

        // check student id validity
        $student = Student::find($request->input('student_id'));
        $phone_number = $request->input('phone_number');

        if (!$student) return $this->sendResponse(Constant::$INVALID_ID, null);
        if ($student->phone_number != $phone_number)
            return $this->sendResponse(Constant::$INVALID_ID, null);

        $student->national_code = $national_code;
        $student->first_name = $request->input('first_name');
        $student->last_name = $request->input('last_name');
        $student->password = Hash::make($request->input('password'));

        $student->token = bin2hex(random_bytes(16));

        // set student verified
        $student->verification_code = null;
        $student->phone_verified_at = Carbon::now();
        $student->save();

        // TODO send registration success message via third party sms platform api

        return $this->sendResponse(Constant::$SUCCESS, ['token' => $student->token]);

    }


}
