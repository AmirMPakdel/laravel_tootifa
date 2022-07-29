<?php

namespace App\Http\Controllers\API\Student\App;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\AppReport;
use App\Models\AppTicket;
use App\Models\AppVersion;
use Illuminate\Http\Request;


class OthersController extends BaseController
{
    public function checkVersion(Request $request){
        $username = $request->input('username');
        $platform = $request->input('platform');
        $user_app_version_code = $request->input('app_version');
        $app_version = AppVersion::where([
            ['platform', $platform],
            ['username', $username],
        ])->orderBy('version_code', 'desc')->first();
        
        if($app_version && ($user_app_version_code < $app_version->version_code)){
            return $this->sendResponse(
                Constant::$SHOULD_UPDATE,
                [
                    "version_name" => $app_version->version_name,
                    "version_code" => $app_version->version_code,
                    "last_changes_list" => $app_version->last_changes_list,
                    "must" => $app_version->must_update,
                    "url" => $app_version->download_link
                ]
            );
        }

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function createAppTicket(Request $request){
        $ticket = new AppTicket();
        $ticket->title = $request->input('title');
        $ticket->platform = $request->input('platform');
        $ticket->platform_version = $request->input('platform_version');
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
