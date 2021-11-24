<?php

namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use Illuminate\Http\Request;


class UserProfileController extends BaseController
{
    public function updateUserProfile(Request $request){
        $profile = $request->input('user')->u_profile()->first();
        $profile->bio = $request->input('bio');
        $profile->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function loadUserProfile(Request $request){
        $profile = $request->input('user')->u_profile()->first();
        
        $result = [
            'first_name' => $request->input('user')->first_name,
            'last_name' => $request->input('user')->last_name,
            'national_code' => $request->input('user')->national_code,
            'phone_number' => $request->input('user')->phone_number,
            'email' => $request->input('user')->email,
            'is_email_verified' => $request->input('user')->email_verified_at != null,
            'm_balance' => $profile->m_balance,
            's_balance' => $profile->s_balance,
            'bio' => $profile->bio,
            'holdable_test_count' => $profile->holdable_test_count,
            'infinit_test_finish_date' => $profile->infinit_test_finish_date,
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }
}
