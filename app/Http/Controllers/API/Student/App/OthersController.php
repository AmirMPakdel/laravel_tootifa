<?php

namespace App\Http\Controllers\API\Student\App;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\AppReport;
use App\Models\AppTicket;
use Illuminate\Http\Request;


class OthersController extends BaseController
{
    public function checkVersion(Request $request){
        $platform = $request->input('platform');
        $app_version = $request->input('app_version');

        if($app_version < Constant::$APP_VERSIONS[$platform]){
            $url = null;
            foreach(Constant::$APP_LINKS as $i){
                if($platform == $i['name']){
                    $url = $i['url'];
                }
            }
            return $this->sendResponse(Constant::$SHOULD_UPDATE, ["url" => $url, "must" => Constant::$MUST_UPDATE]);
        }

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function createAppTicket(Request $request){
        $ticket = new AppTicket();
        $ticket->title = $request->input('title');
        $ticket->platform = $request->input('platform');
        $ticket->phone_number = $request->input('phone_number');
        $ticket->content = $request->input('content');
        $ticket->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function setAppReport(Request $request){
        $report = new AppReport();
        $report->sent_data = $request->input('sent_data');
        $report->recieved_data = $request->input('recieved_data');
        $report->message = $request->input('message');
        $report->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }
}
