<?php


namespace App\Http\Controllers\API\Main;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\SmsManager;
use App\Models\Category;
use App\Models\MainPageProperties;
use App\Models\Tenant;
use App\Models\UProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRegistrationController extends BaseController
{
    public function checkPhoneNumber(Request $request)
    {
        $phone_number = $request->input('phone_number');

        // checking phone number
        $user = User::where([
            ['phone_number', $phone_number],
            ['phone_verified_at', '<>', null]
        ])->exists();

        if ($user)
            return $this->sendResponse(Constant::$REPETITIVE_PHONE_NUMBER, null);
        else
            return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function loginWithPassword(Request $request)
    {
        $phone_number = $request->input('phone_number');
        $password = $request->input('password');

        $user = User::where([
            ['phone_number', $phone_number],
            ['phone_verified_at', '<>', null]
        ])->first();

        if (!$user)
            return $this->sendResponse(Constant::$INVALID_PHONE_NUMBER, null);

        if (!Hash::check($password, $user->password))
            return $this->sendResponse(Constant::$INVALID_PASSWORD, null);

        $user->token = bin2hex(random_bytes(16));
        $user->save();

        return $this->sendResponse(Constant::$SUCCESS, ['token' => $user->token, 'username' => $user->username]);
    }

    public function sendVerificationCode(Request $request)
    {
        $phone_number = $request->input('phone_number');

        // check for verification status
        $user = User::where([
            ['phone_number', $phone_number],
            ['phone_verified_at', '<>', null]
        ])->first();

        if ($user) return $this->sendResponse(Constant::$USER_ALREADY_VERIFIED, null);

        // check for in process user
        $user = User::where([
            ['phone_number', $phone_number],
            ['phone_verified_at', null]
        ])->first();

        // create user if not exist
        if (!$user) {
            $user = new User();
            $user->phone_number = $phone_number;
        }

        //generate and send verification code
        $code = 1111;
        // $code = mt_rand(1000, 9999);
        $user->verification_code = $code;
        $user->save();

        // TODO send verification code via third party sms platform api

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function checkVerificationCode(Request $request)
    {
        $result = User::where('verification_code', $request->input('code'));

        // to prevent a low probable bug
        if ($result->count() > 1)
            return $this->sendResponse(Constant::$INVALID_VERIFICATION_CODE, null);

        $user = $result->first();
        if ($user)
            return $this->sendResponse(Constant::$SUCCESS, ['user_id' => $user->id]);
        else
            return $this->sendResponse(Constant::$INVALID_VERIFICATION_CODE, null);
    }

    public function checkForTenant($username){
        if (Tenant::find($username))
            return $this->sendResponse(Constant::$REPETITIVE_USERNAME, null);
        
        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function completeRegistration(Request $request)
    {
        // check national code
        $national_code = $request->input('national_code');

        if (User::where([
            ['national_code', $national_code],
            ['id', '<>', $request->input('user_id')]
        ])->exists()) return $this->sendResponse(Constant::$REPETITIVE_NATIONAL_CODE, null);

        // check user id validity
        $user = User::find($request->input('user_id'));
        $phone_number = $request->input('phone_number');

        if (!$user) return $this->sendResponse(Constant::$INVALID_ID, null);
        if ($user->phone_number != $phone_number)
            return $this->sendResponse(Constant::$INVALID_ID, null);

        $user->national_code = $national_code;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->password = Hash::make($request->input('password'));

        $user->token = bin2hex(random_bytes(16));

        // set student verified
        $user->verification_code = null;
        $user->phone_verified_at = Carbon::now();

        // generate tenant
        if (Tenant::find($request->input('user_name')))
            return $this->sendResponse(Constant::$REPETITIVE_USERNAME, null);

        $tenant = new Tenant();
        $tenant->id = $request->input('user_name');
        $user->tenant()->save($tenant);
        $user->tenant_id = $tenant->id;

        // generate profile
        $profile = new UProfile();
        $user->u_profile()->save($profile);
        $user->u_profile_id = $profile->id;

        // setting key
        $previous = User::find(User::where('id', '<', $user->id)->max('id'));
        if ($previous) {
            $sum = (int) (hexdec($previous->key) + 1);
            $new_code = dechex($sum);
            $user->key = $new_code;
        } else
            $user->key = "1111";

        $user->save();

        // generate user defaults
        $tenant->run(function () {
            MainPageProperties::create(['page_title' => "عنوان"]);
            foreach(Constant::$DEFAULT_CATEGORIES as $cat) Category::create(['title' => $cat]);
        });
        SmsManager::generateDefaultSmsTypes($tenant);
        

        // TODO send registration success message via third party sms platform api

        return $this->sendResponse(Constant::$SUCCESS, ['token' => $user->token]);
    }
}
