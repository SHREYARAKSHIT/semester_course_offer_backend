<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\ControllerAPI;
use Illuminate\Support\Facades\DB;
class UserController extends ControllerAPI
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', );
        $this->middleware('AuthCheck:stu');
    }

    function sendSms(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'number' => 'required|string',
            'msg' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Request !',
            ], 401);
        }

        $number = $request->number;
        $msg = $request->msg;
        //   $res = sendSMS($number, $msg);
        $session_year = getDepartment('academic', true);
        return $this->sendResponse($session_year, "Recordds");
    }

    public function index()
    {
        $data = [
            'title' => 'Welcome to Tutsmake.com',
            'date' => date('m/d/Y')
        ];

        $pdf = PDF::loadView('testPDF', $data);

        return $pdf->download('tutsmake.pdf');
    }

   
}
