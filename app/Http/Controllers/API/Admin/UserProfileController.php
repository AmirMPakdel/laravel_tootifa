<?php

namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Includes\UploadManager;
use Illuminate\Http\Request;


class UserProfileController extends BaseController
{
    public function updateUserProfile(Request $request){
        $user = $request->input('user');

        // user
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->national_code = $request->input('national_code');
        if ($user->email != $request->input('email'))
            $user->email_verified_at = null;
        
        // profile
        $profile = $user->u_profile()->first();
        $profile->bio = $request->input('bio');
        $profile->state = $request->input('state');
        $profile->city = $request->input('city');

        $file_state = $request->input("file_state");
        if (!$file_state) return $this->sendResponse(Constant::$NO_FILE_STATE, null);

        $result = UploadManager::updateFileState(
            $file_state,
            tenant()->id,
            1,
            $profile,
            "national_cart_image",
            false,
            false,
            $request->input('upload_key')
        );

        if($result == Constant::$SUCCESS){
            $user->save();
            $profile->save();
        }

        return $this->sendResponse($result, null);
    }


    public function updateUserBankInfo(Request $request){
        $user = $request->input('user');
        $profile = $user->u_profile()->first();
        $profile->account_owner_first_name = $request->input('account_owner_first_name');
        $profile->account_owner_last_name = $request->input('account_owner_last_name');
        $profile->bank = $request->input('bank');
        $profile->account_number = $request->input('account_number');
        $profile->shaba_number = $request->input('shaba_number');
        $profile->credit_cart_number = $request->input('credit_cart_number');
        $profile->save();
        
        return $this->sendResponse(Constant::$SUCCESS, null);
    }


    // TODO calculate total income
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
            'state' => $profile->state,
            'city' => $profile->city,
            'holdable_test_count' => $profile->holdable_test_count,
            'infinit_test_finish_date' => $profile->infinit_test_finish_date,
            'total_saved_income' => 0,
            'account_owner_first_name' => $profile->account_owner_first_name,
            'account_owner_last_name' => $profile->account_owner_last_name,
            'bank' => $profile->bank,
            'account_number' => $profile->account_number,
            'shaba_number' => $profile->shaba_number,
            'credit_cart_number' => $profile->credit_cart_number,
            'national_cart_image' => $profile->national_cart_image
        ];

        return $this->sendResponse(Constant::$SUCCESS, $result);
    }
}
