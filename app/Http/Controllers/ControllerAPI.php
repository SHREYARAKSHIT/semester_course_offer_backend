<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;


class ControllerAPI extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        //parent::__construct();
        $this->middleware('auth:sanctum', ['except' => ['login','validateUser','GetBiometicAttendance','downloadPh4', 'test', 'login_api', 'validateSingleLogin', 'sendEmailBulkMannualy', 'TokenError', 'UpdatePassword', 'sanctum/csrf-cookie']]);
    }
    public function sendResponse($result, $message, $recordCount = null)
    {


        $response = [
            'status' => true,
            'responseCode' => 200,
            'message' => $message,
            'data'    => $result,
            'recordCount'    => isset($recordCount) ? $recordCount : (isset($result) ? is_array($result) ? count($result) : 0 : 0),
            'timestamp' => date('d-m-Y H:s:i a'),
        ];


        return response()->json($response, 200);
    }
    public function sendError($shortMsg = 'Failed', $errorMessages = 'Something Went Worng.Please try Again !', $result = [], $code = 402)
    {
        $response = [
            'status' => false,
            'responseCode' => $code,
            'shortMsg' => $shortMsg,
            'message' => $errorMessages,
            'result' => $result,
            'timestamp' => date('d-m-Y H:s:i a')
        ];


        // if (!empty($errorMessages)) {
        //     $response['result'] = $result;
        // }


        return response()->json($response, 200);
    }
}
