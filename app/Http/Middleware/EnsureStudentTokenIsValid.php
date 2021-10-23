<?php

namespace App\Http\Middleware;

use App\Includes\Constant;
use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureStudentTokenIsValid
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
        $token = null;
        switch ($request->method()) {
            case 'POST':
                $token = $request->input('token');
                break;
            case 'GET':
                $token = $request->query('token');
                break;
            default:
                return $this->sendResponse(Constant::$INVALID_REQUEST, null);
        }

        $student = Student::where('token', $token)->first();
        if (!$student)
            return $this->sendResponse(Constant::$INVALID_TOKEN, null);

        $request->request->add(['student' => $student]);
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
