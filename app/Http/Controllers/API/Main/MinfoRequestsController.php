<?php


namespace App\Http\Controllers\API\Main;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\BankingPortal;

class MinfoRequestsController extends BaseController
{   
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
