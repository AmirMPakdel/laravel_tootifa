<?php


namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($code, $result)
    {
        $response = [
            'result_code' => $code,
            'data'    => $result,
        ];

        return response()->json($response, 200);
    }
}
