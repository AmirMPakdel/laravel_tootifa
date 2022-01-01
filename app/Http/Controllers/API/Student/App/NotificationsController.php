<?php

namespace App\Http\Controllers\API\Student\App;

use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\StudentNotification;
use App\Models\Tenant;
use Illuminate\Http\Request;


class NotificationsController extends BaseController
{
    public function getLastNotificationsList(Request $request)
    {
        $targets = $request->input('targets');
        $notifications = [];

        foreach ($targets as $target) {
            $target = (object)$target;
            $tenant = Tenant::find($target->username);

            if ($tenant == null) {
                array_push($notifications, Constant::$USER_NOT_FOUND);
                continue;
            }

            $notification = $tenant->run(function () use ($target) {
                $messages = StudentNotification::where('student_id', $target->student_id)->get()
                    ->map(function( $notif ) {
                        return [
                            'title' => $notif->title,
                            'content' => $notif->content
                        ];
                    });
                
                return [
                    'username' => $target->username,
                    'messages' => $messages
                ];
            });

            array_push($notifications, $notification);
        }

        return $this->sendResponse(Constant::$SUCCESS, $notifications);
    }
}
