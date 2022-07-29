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

        $user_app_version = AppVersion::where([
            ['platform', $platform],
            ['username', $username],
            ['version_code', $user_app_version_code],
        ])->first();

        if(!$user_app_version)
            return $this->sendResponse(Constant::$INVALID_VERSION_CODE, null);

        $last_app_version = AppVersion::where([
            ['platform', $platform],
            ['username', $username],
        ])->orderBy('version_code', 'desc')->first();
        
        if($last_app_version && ($user_app_version_code < $last_app_version->version_code)){
            return $this->sendResponse(
                Constant::$SHOULD_UPDATE,
                [
                    "version_name" => $last_app_version->version_name,
                    "version_code" => $last_app_version->version_code,
                    "last_changes_list" => $last_app_version->last_changes_list,
                    "must" => $user_app_version->must_update,
                    "url" => $last_app_version->download_link
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
