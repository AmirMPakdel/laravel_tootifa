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
}
