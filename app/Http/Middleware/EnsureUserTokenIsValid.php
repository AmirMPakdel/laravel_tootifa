<?php

namespace App\Http\Middleware;

use App\Includes\Constant;
use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureUserTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where('token', $request->input('token'))->first();
        if (!$user)
            return $this->sendResponse(Constant::$INVALID_TOKEN, null);

        if($user->tenant() == Tenant::find($request->header('X-TENANT')))
            return $this->sendResponse(Constant::$INVALID_TOKEN, null);

        $request->request->add(['user' => $user]);
        return $next($request);
    }

    public function sendResponse($code, $result)
    {
        $response = [
            'result_code' => $code,
            'data'    => $result,
        ];

        return response()->json($response, 200);
    }
}
