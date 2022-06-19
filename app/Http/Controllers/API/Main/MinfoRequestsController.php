<?php


namespace App\Http\Controllers\API\Main;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\BankingPortal;
use App\Models\Course;
use App\Models\Tenant;
use Illuminate\Http\Request;

class MinfoRequestsController extends BaseController
{
    public function requestForValidationCheck(Request $request)
    {
        $course_id = $request->input('course_id');
        $user = $request->input('user');

        $result = Tenant::find($user->tenant_id)->run(function() use ($course_id){
            $c = Course::find($course_id);
            $c->validation_status = Constant::$VALIDATION_STATUS_NOT_VALID;
            $c->save();
        });

        //TODO set a requset in minfo database
        

        return $this->sendResponse(Constant::$SUCCESS, null);
    }
    
    public function getMinfoBankingPortals(){
        $portals = BankingPortal::all()->map(function($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'name' => $p->name,
                'logo' => $p->logo,
            ];
        });

        return $this->sendResponse(Constant::$SUCCESS, $portals);
    }
}
