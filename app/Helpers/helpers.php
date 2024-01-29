<?php

use App\Models\TheamSetting;
use Illuminate\Support\Facades\DB;

function TheamSetting($id = '', $column)
{
    $conf = TheamSetting::where('user_id', '=', $id)->first([$column]);

    return isset($conf->$column) ? $conf->$column : null;
}

function getUserAuth($id)
{
    // $id = Auth::user()->id;
    if ($id) {
        $auth_id = array();
        $users = DB::select("  (
            SELECT a.auth_id
            FROM users a
            WHERE a.id='$id')
            UNION 
            (
                SELECT a.auth_id
                FROM emp_basic_details a
                WHERE a.emp_no='$id') UNION 
              
            (
        SELECT a.auth_id
        FROM user_auth_types a
        WHERE a.id='$id') UNION 
       
        (
        SELECT a.auth_id
        FROM user_auth_types_extension a
        WHERE a.id='$id' AND a.status='A')");
        foreach ($users as $key => $value) {
            array_push($auth_id, $value->auth_id);
        }
        // print_r($auth_id);
        // exit;
        return $auth_id;
    } else {
        return false;
    }
}

function SendEmail($email, $message)
{
}

function sendSMS($number, $msg, $senderId = '')
{

    $username = "IITISM";
    $password = "Vrbest1!";

    $message = $msg;
    $numbers = '91' . $number;

    $url = "http://185.255.8.59/sms/1/text/query?username=$username&password=$password&to=$numbers&text=$message&from=IITISM&principalEntityId=1101377470000013605&contentTemplateId";

    //echo $url; //exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, str_replace(' ', '%20', $url)); //curl_escape($ch,$url));//
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,  CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $result = curl_exec($ch);
    if (curl_exec($ch) === false) {
        $r['error'] = curl_error($ch);
    } else {
        $r['success'] = $result;
        //echo 'Operation completed without any errors';
    }
    //	print_r($r); exit;
    return $r;
}