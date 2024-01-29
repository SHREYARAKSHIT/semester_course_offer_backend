<?php

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\MenuModel;
use Illuminate\Support\Facades\DB;
use App\Notifications\UserNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

function timetablecheck($subjects, $tocheck)
{
    echo "demo git 2";
    return false;
}

function decodeFormData($encoded)
{
    $encoded = base64_decode($encoded);

    $decoded = "";
    for ($i = 0; $i < strlen($encoded); $i++) {
        $b = ord($encoded[$i]);
        $a = $b ^ 10; // 
        print_r(chr($a));
        $decoded .= chr($a);
    }
    return base64_decode(base64_decode($decoded));
}

function getAllPendingForms($request_from)
{
    return $pending = DB::table('tms_form_submission_progress as a')
        ->select(DB::raw('count(a.form_type) as total_pending,a.*,concat_ws(" ",b.first_name,b.middle_name,b.last_name) as stu_name,b.photopath,b.dept_id,c.course_id,c.branch_id,c.other_rank'))
        ->join('user_details as b', 'a.admn_no', 'b.id')
        ->join('stu_academic as c', 'a.admn_no', 'c.admn_no')
        ->where('a.submitted_to_auth', $request_from)
        ->where('a.status', '1')
        ->where('a.application_status', 'pending')
        ->groupBy('a.form_type')
        ->orderBy('id', 'asc')
        ->get();
}

function getFormHistory($dept_id, $form_type, $request_from, $limit = 1, $page = 1, $filter = null)
{

    //   DB::enableQueryLog();
    $data = DB::table('tms_form_submission_progress as a')
        ->select(DB::raw('a.*,concat_ws(" ",b.first_name,b.middle_name,b.last_name) as stu_name,b.photopath,b.dept_id as dept,c.course_id,c.branch_id
        ,c.other_rank,(select if(fs.current_application_status="Approvedandforword","Approved and Forwarded",fs.current_application_status) from tms_ph1 as fs where fs.id=a.form_id) as last_app_status'))
        ->join('user_details as b', 'a.admn_no', 'b.id')
        ->join('stu_academic as c', 'a.admn_no', 'c.admn_no')
        // ->where('a.submitted_by_dept_id', $dept_id)

        ->where(function ($query) use ($dept_id) {
            $query->where('a.submitted_by_dept_id', $dept_id)
                ->orWhere('a.submitted_to_dept_id', $dept_id);
        })

        //  ->where('a.submitted_by_auth', $request_from)
        // ->orWhere('a.submitted_to_auth', $request_from)
        ->where(function ($query) use ($request_from) {
            $query->where('a.submitted_by_auth', $request_from)
                ->orWhere('a.submitted_to_auth', $request_from);
        })
        ->where('a.form_type', $form_type)
        ->where('a.application_status', '<>', 'pending')
        ->when($filter, function ($query) use ($filter) {
            //  $query->orWhere('concat_ws(" ",b.first_name,b.middle_name,b.last_name)', 'LIKE', '%' . "$filter" . '%');
            $query->where('b.first_name', 'LIKE', '%' . "$filter" . '%');
            $query->orWhere('b.middle_name', 'LIKE', '%' . "$filter" . '%');
            $query->orWhere('b.last_name', 'LIKE', '%' . "$filter" . '%');
            $query->orWhere('b.id', 'LIKE', '%' . "$filter" . '%');
            $query->orWhere('b.dept_id', 'LIKE', '%' . "$filter" . '%');
            //  $query->orWhere('a.last_app_status', 'LIKE', '%' . $filter . '%');
        })
        ->orderBy('id', 'asc')
        ->groupBy('a.form_id')
        ->paginate($limit);
    //  dd(DB::getQueryLog());

    return $data;
}

function knowoYourChannel($type = null)
{
    return DB::table('tms_channel_masters as a')
        ->where('a.channel_for', $type)
        ->select(DB::raw('a.*,b.*,c.type AS child_auth_name,group_concat(DISTINCT d.type SEPARATOR " / ") AS parent_auth_name'))
        ->Join('tms_channels as b', 'b.cm_id', 'a.id')
        ->leftJoin('auth_types as c', 'c.id', 'b.child_auth')
        ->leftJoin('auth_types as d', 'd.id', 'b.parent_auth')
        ->orderBy('b.level', 'asc')
        ->groupByRaw('b.cm_id,b.level')
        ->get();
}

function GetFromProgress($form_type, $admn_no, $from = null)
{

    //     $sql = "SELECT a.*,c.type AS submitted_by_auth_name,c.type AS submitted_to_auth_name
    // FROM `tms_form_submission_progress` AS `a`
    // LEFT JOIN `auth_types` AS `c` ON `c`.`id` = `a`.`submitted_by_auth`
    // LEFT JOIN `auth_types` AS `d` ON `d`.`id` = `a`.`submitted_to_auth`
    // WHERE `a`.`admn_no` = '$admn_no' AND `a`.`form_type` = '$form_type'
    // ORDER BY `a`.`level` ASC";

    //     echo $sql;
    //     exit;

    DB::enableQueryLog();
    $data = DB::table('tms_form_submission_progress as a')
        ->where('a.admn_no', $admn_no)
        ->where('a.form_type', $form_type)
        ->select(DB::raw(' a.*,c.type AS submitted_by_auth_name,d.type AS submitted_to_auth_name,CONCAT_WS(" ",ud.first_name,ud.middle_name,ud.last_name) AS submitted_by_name
        ,CONCAT_WS(" ",udd.first_name,udd.middle_name,udd.last_name) AS submitted_to_name'))
        ->leftJoin('user_details as ud', 'ud.id', 'a.submitted_by')
        ->leftJoin('user_details as udd', 'udd.id', 'a.submitted_to')
        ->leftJoin('auth_types as c', 'c.id', 'a.submitted_by_auth')
        ->leftJoin('auth_types as d', 'd.id', 'a.submitted_to_auth')
        ->orderBy('a.id', 'asc')->get();

    // dd(DB::getQueryLog());
    return $data;

    //     $sql = "SELECT a.*,c.type AS submitted_by_auth_name,c.type AS submitted_to_auth_name
    // FROM `tms_form_submission_progress` AS `a`
    // LEFT JOIN `auth_types` AS `c` ON `c`.`id` = `a`.`submitted_by_auth`
    // LEFT JOIN `auth_types` AS `d` ON `d`.`id` = `a`.`submitted_to_auth`
    // WHERE `a`.`admn_no` = '$admn_no' AND `a`.`form_type` = '$form_type'
    // ORDER BY `a`.`level` ASC";

    //     echo $sql;
    //     exit;

    //     $data = DB::select(DB::raw("SELECT a.*,c.type AS submitted_by_auth_name,c.type AS submitted_to_auth_name
    //     FROM `tms_form_submission_progress` AS `a`
    //     LEFT JOIN `auth_types` AS `c` ON `c`.`id` = `a`.`submitted_by_auth`
    //     LEFT JOIN `auth_types` AS `d` ON `d`.`id` = `a`.`submitted_to_auth`
    //     WHERE `a`.`admn_no` = :admn_no AND `a`.`form_type` = :form_type
    //     ORDER BY `a`.`level` ASC"), array(
    //         'admn_no' => $admn_no,
    //         'form_type' => $form_type,
    //     ));
    //     return  $data;
}

function getChannel($channel_for, $admn_no, $request_from, $dept_id = null, $action = null, $auth = null, $lastSubmitStatus = null, $lastSubmitChannel = null)
{
    // echo $dept_id;
    // exit;

    $userchannellevel = DB::table('tms_channel_masters as a')
        ->join('tms_channels as b', 'a.id', 'b.cm_id')
        ->where('a.channel_for', $channel_for)
        ->where('b.child_auth', $request_from)
        ->first();

    // print_r($userchannellevel->level );exit;

    $extrajoin = "";
    $condition = " = (z.submiited_to_level)";
    $extraColRet = "";
    $extraGroup = "";
    $extrawhere = "";
    if ($action == 'return') {
        $et = ($userchannellevel) ? $userchannellevel->level : '(z.submiited_to_level)';
        $condition = " < $et";
        $extraColRet = " ,ps.submitted_by_dept_id AS dept_id_ret,ps.submitted_by AS submitted_ret";
        $extrajoin = " inner JOIN tms_form_submission_progress ps ON ps.submitted_by_auth=z.child_auth AND ps.form_type=z.form_type and ps.status IN (1,2) AND ps.admn_no='$admn_no'";
        $extraGroup = "group BY ps.submitted_by_auth,ps.submitted_by";
    } else {
        $condition = ($lastSubmitStatus == 'return') ? " = $lastSubmitChannel" : " = (z.submiited_to_level)";
        $extraGroup = "GROUP BY z.parent_auth";
        $extrawhere = " AND b.submitted_to_auth='$request_from'";
    }
    if (!$dept_id) {
        $dept_id = getDepartmentById(Auth::user()->id);
    }

    $sql = "SELECT z.*,d.`level` AS to_level,(SELECT level FROM tms_channels WHERE cm_id=z.cm_id AND child_auth='$request_from' group by level) AS current_level  $extraColRet FROM (SELECT z.*,ua.`type` AS child_auth_name,uaa.`type` AS parent_auth_name
    FROM 
     (
    SELECT z.*,c.id AS tms_c_id,c.child_auth,c.parent_auth,c.`level` AS c_level,c.`status` AS cm_status,c.cm_id,(case when c.parent_auth IN ('hod','dpgc') then 1 else c.auth_share END) AS auth_share
    FROM (
    SELECT z.*
    FROM (
    SELECT a.*,b.id AS progress_id,b.admn_no,b.dept_id,b.form_id,b.form_type,b.submitted_by,b.submitted_by_auth,b.submitted_by_dept_id,b.submitted_to,b.submitted_to_auth,b.submitted_to_dept_id as submitted_to_dept_ids,b.`level`,b.to_level AS submiited_to_level,b.application_status,b.created_by
    FROM tms_channel_masters a
    LEFT JOIN tms_form_submission_progress b ON a.channel_for=b.form_type AND b.admn_no='$admn_no' AND b.`status`='1' 
    AND b.`application_status`='pending' $extrawhere
    WHERE a.channel_for='$channel_for'
    ORDER BY b.admn_no,b.form_type,b.`level` DESC
    LIMIT 10000000)z
    GROUP BY z.admn_no,z.form_type)z
    LEFT JOIN tms_channels c ON z.id=c.cm_id AND (CASE WHEN z.submitted_to_auth IS NULL THEN c.child_auth='$request_from' ELSE 1=1 END) /*c.child_auth='stu'*/ AND 
     (CASE WHEN z.submitted_to_auth IS NULL THEN c.`LEVEL`=0 ELSE c.`level`  $condition END))z
    LEFT JOIN auth_types ua ON z.child_auth=ua.id
    LEFT JOIN auth_types uaa ON z.parent_auth=uaa.id) z
    LEFT JOIN  tms_channels d ON z.cm_id=d.cm_id AND (case when z.parent_auth IS NULL then z.child_auth else z.parent_auth END)=d.child_auth
    $extrajoin
    WHERE tms_c_id IS NOT null $extraGroup order by z.c_level asc";
    //   return print_r($sql);
//   exit;
    //   DB::enableQueryLog();
    $forwordChannel = DB::select(
        DB::raw("SELECT z.*,d.`level` AS to_level,(SELECT level FROM tms_channels WHERE cm_id=z.cm_id AND child_auth='$request_from' group by level) AS current_level  $extraColRet FROM (SELECT z.*,ua.`type` AS child_auth_name,uaa.`type` AS parent_auth_name
    FROM 
     (
    SELECT z.*,c.id AS tms_c_id,c.child_auth,c.parent_auth,c.`level` AS c_level,c.`status` AS cm_status,c.cm_id,(case when c.parent_auth IN ('hod','dpgc') then 1 else c.auth_share END) AS auth_share
    FROM (
    SELECT z.*
    FROM (
    SELECT a.*,b.id AS progress_id,b.admn_no,b.dept_id,b.form_id,b.form_type,b.submitted_by,b.submitted_by_auth,b.submitted_by_dept_id,b.submitted_to,b.submitted_to_auth,b.submitted_to_dept_id as submitted_to_dept_ids,b.`level`,b.to_level AS submiited_to_level,b.application_status,b.created_by
    FROM tms_channel_masters a
    LEFT JOIN tms_form_submission_progress b ON a.channel_for=b.form_type AND b.admn_no=:admn_no AND b.`status`='1' 
    AND b.`application_status`='pending' $extrawhere
    WHERE a.channel_for=:channel_for
    ORDER BY b.admn_no,b.form_type,b.`level` DESC
    LIMIT 10000000)z
    GROUP BY z.admn_no,z.form_type)z
    LEFT JOIN tms_channels c ON z.id=c.cm_id AND (CASE WHEN z.submitted_to_auth IS NULL THEN c.child_auth=:request_from ELSE 1=1 END) /*c.child_auth='stu'*/ AND 
     (CASE WHEN z.submitted_to_auth IS NULL THEN c.`LEVEL`=0 ELSE c.`level`  $condition END))z
    LEFT JOIN auth_types ua ON z.child_auth=ua.id
    LEFT JOIN auth_types uaa ON z.parent_auth=uaa.id) z
    LEFT JOIN  tms_channels d ON z.cm_id=d.cm_id AND (case when z.parent_auth IS NULL then z.child_auth else z.parent_auth END)=d.child_auth
    $extrajoin
    WHERE tms_c_id IS NOT null $extraGroup order by z.c_level asc"),
        array(
            'admn_no' => $admn_no,
            'channel_for' => $channel_for,
            'request_from' => $request_from
        )
    );
    //  print_r($forwordChannel);die;
    foreach ($forwordChannel as $key => $value) {

        $forwordingToAuth = ($action == 'return') ? $value->child_auth : $value->parent_auth;
        //  $forwordingFromAuth = $value->child_auth;
        $forwordingFromAuth = ($action == 'return') ? $value->child_auth : $value->submitted_to_auth; // $value->submitted_to_auth;exit;  
        $forwordingFromDept = ($action == 'return') ? $value->submitted_by_dept_id : $value->submitted_to_dept_ids;
        $user_id = ($action == 'return') ? $value->submitted_ret : $value->submitted_to;
        $auth_share = $value->auth_share;
        //  print_r($forwordChannel[$key]);
        if ($action == 'return') {
            $dept_id = $value->dept_id_ret;
        }

        if ($auth_share) {

            if ($forwordingToAuth == 'ft' || $forwordingToAuth == 'stu') {
                $record = getForWordingChannelUser($forwordingToAuth, $dept_id, $user_id, $admn_no);
            } else {
                $record = getForWordingChannelUser($forwordingToAuth, $dept_id);
            }
        } else {
          
            $record = getForWordingChannelUser($forwordingToAuth);
        }
        // echo $forwordingToAuth;
        // print_r($record);
        // exit;
        //  echo  $forwordingFromAuth;exit;

        if ($action == 'return') {
            $to = getForWordingChannelUser($forwordingFromAuth, $dept_id, $user_id);
        } else {
            $to = getForWordingChannelUser($forwordingFromAuth, $forwordingFromDept);
        }

        //$to = getForWordingChannelUser($forwordingFromAuth, $forwordingFromDept);

        // 

        // print_r($to);
        // echo count($to);
        // echo (count($to) > 0) ? $to[0]->id :  'a';
        // exit;



        $forwordChannel[$key]->forword_to_id = ($record) ? $record[0]->id : null;
        $forwordChannel[$key]->forword_to_auth = $forwordingToAuth;
        $forwordChannel[$key]->forword_to_user_name = ($record) ? $record[0]->user_name : null;
        $forwordChannel[$key]->forword_to_dept = ($record) ? $record[0]->dept_id : null;
        $forwordChannel[$key]->forword_to_photopath = ($record) ? $record[0]->photopath : null;
        $forwordChannel[$key]->submitted_to_dept_id = ($record) ? $record[0]->dept_id : null;

        $forwordChannel[$key]->forword_from_dept = (count($to) > 0) ? $to[0]->dept_id : $dept_id;
        $forwordChannel[$key]->forword_from_auth = ($forwordingFromAuth) ? $forwordingFromAuth : $request_from;
        $forwordChannel[$key]->forword_from_id = (count($to) > 0) ? $to[0]->id : Auth::user()->id;
        $forwordChannel[$key]->forword_from_user_name = (count($to) > 0) ? $to[0]->user_name : Auth::user()->id;
    }
    //d  exit;
    // print_r($forwordChannel);
    // exit;
    return $forwordChannel;
}

function getDesignationByUser($id)
{
    return DB::table('emp_basic_details as a')
        ->select(DB::raw('a.emp_no,b.id,b.name,c.research_interest_eng as area_of_specilization'))
        ->join('designations as b', 'a.designation', 'b.id')
        ->leftJoin('web_people as c', 'a.emp_no', 'c.id')
        ->where('a.emp_no', $id)
        ->get();

}

function getEmpByDeptId($dept_id)
{
    $data = DB::table('user_details as a')
        ->where('a.dept_id', $dept_id)
        ->select(DB::raw('a.id,a.photopath,CONCAT_WS(" ",a.first_name,a.middle_name,a.last_name) AS user_name,c.auth_id,a.dept_id,wp.research_interest_eng as area_of_specilization'))
        ->leftJoin('web_people as wp', 'a.id', 'wp.id')
        ->join('users as b', function ($joins) {
            $joins->on('b.id', '=', 'a.id')->where('b.status', 'A');
        })
        ->join('emp_basic_details as c', function ($join) {
            $join->on('c.emp_no', '=', 'a.id')->where('c.auth_id', 'ft');
        })
        ->get();

    return $data;
}
function GetDPGCByDept($dept_id)
{
    $data = DB::table('user_auth_types as a')
        ->where('a.auth_id', 'dpgc')
        ->select(DB::raw('a.id,a.auth_id,CONCAT_WS(" ",b.first_name,b.middle_name,b.last_name) dpgc_name,b.photopath,b.dept_id'))
        ->join('user_details as b', function ($joins) use ($dept_id) {
            $joins->on('b.id', '=', 'a.id')->where('b.dept_id', $dept_id);
        })
        ->first();
    return $data;
}

function getForWordingChannelUser($auth_id, $dept_id = null, $user_id = null, $admn_no = null)
{

    //  echo $dept_id . "|" . $auth_id . "||".$user_id;
    //  exit;

    //    DB::enableQueryLog();

    if ($auth_id == 'ft') {
        $data = DB::table('emp_basic_details as a')
            ->where('a.auth_id', $auth_id)
            ->where('b.id', $user_id)
            ->select(DB::raw('a.*,CONCAT_WS(" ",b.first_name,b.middle_name,b.last_name) AS user_name,b.id,b.email,b.photopath,b.dept_id'))
            ->join('user_details as b', 'a.emp_no', 'b.id')
            ->get();

        if (count($data) == 0) {
            $data = DB::table('project_guide as a')
                ->where('a.admn_no', $admn_no)
                //->where('b.id', $user_id)
                ->select(DB::raw('a.*,CONCAT_WS(" ",b.first_name,b.middle_name,b.last_name) AS user_name,b.id,b.email,b.photopath,b.dept_id'))
                ->join('user_details as b', 'a.guide', 'b.id')
                ->get();
        }

    } elseif ($auth_id == 'stu') {
        $data = DB::table('user_details as a')
            ->select(DB::raw('a.*,CONCAT_WS(" ",a.first_name,a.middle_name,a.last_name) AS user_name'))
            ->where('a.id', $user_id)->get();
    } else {
        $data = DB::table('user_auth_types as a')
            ->where('a.auth_id', $auth_id)
            ->select(DB::raw('a.*,CONCAT_WS(" ",b.first_name,b.middle_name,b.last_name) AS user_name,b.email,b.photopath,b.dept_id'))
            ->join('user_details as b', function ($joins) use ($dept_id) {
                $joins->on('b.id', '=', 'a.id')->when($dept_id, function ($query) use ($dept_id) {
                    return $query->where('b.dept_id', $dept_id);
                });
            })
            ->get();
    }

    //  print_r(DB::getQueryLog());
    // echo $data;
    // exit;

    return $data;
}


function SendNotification($user_id_to, $auth, $module_id = null, $title = null, $description, $path = null, $type = "")
{
    $data = array(
        "user_to" => $user_id_to,
        "user_from" => Auth::user()->id,
        "auth_id" => $auth,
        "notice_title" => $title,
        "description" => $description,
        "module_id" => $module_id,
        "notice_path" => $path,
        "data" => $description,
    );

    $user = User::find(Auth::user()->id);
    $user->notify(new UserNotification($data, $user_id_to));
}

function getDepartmentById($id)
{
    $dept = DB::table('user_details')
        ->where('id', $id)
        ->selectRaw('dept_id')
        ->first();

    return $dept->dept_id;
}

function saveToLog($table_name, $field_name, $old_value, $new_value, $log_pk = null)
{
    $save = array(
        "table_name" => $table_name,
        "log_pk" => $log_pk,
        "field_name" => $field_name,
        "old_value" => $old_value,
        "new_value" => $new_value,
        "created_by" => Auth::user()->id,
    );

    DB::table('log_master')->insertGetId($save);
    return true;
}

function getDepartment($type = 'academic', $onlyActive = false)
{
    if ($onlyActive) {
        $onlyActive = 1;
    }
    if ($type) {
        $type = $type;
    }
    $department = DB::table('cbcs_departments')->select('cbcs_departments.*')->when($onlyActive, function ($query) use ($onlyActive) {
        return $query->where('cbcs_departments.status', '=', "$onlyActive");
    })->when($type, function ($query) use ($type) {
        return $query->where('cbcs_departments.type', '=', "$type");
    })->orderBy('cbcs_departments.id', 'asc')->get();
    return $department;
}

function StuDetails($admn_no)
{
    // echo $admn_no;exit;
    $stuData = DB::table('user_details')
        ->where('user_details.id', $admn_no)
        ->select(
            DB::raw(
                'user_details.*,CONCAT_WS(" ",user_details.first_name,user_details.middle_name,user_details.last_name) AS user_name,
cbcs_departments.name AS dept_name,user_details.dept_id,emaildata.domain_name,stu_prev_certificate.signpath,stu_academic.course_id,stu_academic.branch_id,
stu_academic.admn_based_on,stu_academic.enrollment_year,stu_academic.other_rank,stu_academic.semester,
            stu_details.admn_date,
            user_other_details.mobile_no,cbcs_branches.name as branch_name'
            )
        )
        ->join('cbcs_departments', 'cbcs_departments.id', 'user_details.dept_id')
        ->join('stu_academic', 'stu_academic.admn_no', 'user_details.id')
        ->LeftJoin('cbcs_branches', 'stu_academic.branch_id', 'cbcs_branches.id')
        ->join('stu_details', 'stu_details.admn_no', 'user_details.id')
        ->join('user_other_details', 'user_other_details.id', 'user_details.id')
        ->leftJoin('emaildata', 'emaildata.admission_no', 'user_details.id')
        ->leftJoin('stu_prev_certificate', 'stu_prev_certificate.admn_no', 'user_details.id')
        ->groupBy('user_details.id')
        ->first();



    return $stuData;
}

function GetSession($onlyActive = false)
{
    if ($onlyActive) {
        $onlyActive = 1;
    }
    $session_year = DB::table('mis_session')->select('mis_session.*')->when($onlyActive, function ($query) use ($onlyActive) {
        return $query->where('mis_session.active', '=', "$onlyActive");
    })->orderBy('mis_session.id', 'asc')->get();
    return $session_year;
}

function GetSessionYear($onlyActive = false)
{
    if ($onlyActive) {
        $onlyActive = 1;
    }
    $session_year = DB::table('mis_session_year')->select('mis_session_year.*')->when($onlyActive, function ($query) use ($onlyActive) {
        return $query->where('mis_session_year.active', '=', "$onlyActive");
    })->orderBy('mis_session_year.id', 'desc')->get();
    return $session_year;
}

function FileUpload($file, $path, $name = null)
{
    define('SEPARATOR', '/');
    define('WWW_ROOT', base_path());
    // print_r($file);
    if (!empty($file)) {
        $targetFile = WWW_ROOT . SEPARATOR . $path . SEPARATOR . $file['name'];
        // print_r($file);
        $targetFile = validateAndSetFileName($targetFile);
        $validFileContent = validateFileContent($file['tmp_name'], $targetFile);
        //  print_r($validFileContent);
        if ($targetFile && !$validFileContent['bError']) {
            $file_name = str_replace(' ', '_', pathinfo($targetFile, PATHINFO_FILENAME));
            $ext = pathinfo($targetFile, PATHINFO_EXTENSION);
            $timestamp = time();
            $savedFileName = isset($name) ? $name . "." . $ext : $file_name . "_" . $timestamp . "." . $ext;
            $original_file_path = WWW_ROOT . SEPARATOR . $path;
            is_upload_dir_exists($original_file_path);
            $tempFile = $file['tmp_name'];
            $saveOriginalFile = $path . SEPARATOR . $savedFileName;
            $tempFile = $file['tmp_name'];
            $targetFile = WWW_ROOT . SEPARATOR . $saveOriginalFile;

            @list($width, $height, $type, $attr) = getimagesize($tempFile);
            //saving image for user in folder and database
            $moveSuccessfull = move_uploaded_file($tempFile, $targetFile);
            if ($moveSuccessfull && file_exists($targetFile)) {
                $response['file_name'] = $savedFileName;
                $file_path = SEPARATOR . $saveOriginalFile;
                return $file_path;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function cryptoJsAesDecrypt($passphrase, $jsonString)
{
    $jsondata = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsondata["s"]);
        $iv = hex2bin($jsondata["iv"]);
    } catch (Exception $e) {
        return $e;
    }
    $ct = base64_decode($jsondata["ct"]);
    $concatedPassphrase = $passphrase . $salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return json_decode($data, true);
}

function getFormsByAuths($auths)
{
    return DB::table('tms_form')->whereIn('initiated_from', $auths)->where('status', '1')->orderByRaw('CAST(form_no as SIGNED) asc')->get();
}

function getUserAuths($id, $olnyauth = false)
{
    $id = Auth::user()->id;
    if ($olnyauth) {
        $users = DB::select("(
            SELECT a.auth_id
            FROM user_auth_types a
            WHERE a.id='$id') UNION
            (
            SELECT a.auth_id
            FROM emp_basic_details a
            WHERE a.emp_no='$id') UNION
            (
            SELECT a.auth_id
            FROM users a
            WHERE a.id='$id')
            UNION
            (
            SELECT a.auth_id
            FROM user_auth_types_extension a
            WHERE a.id='$id' AND a.status='A')");
    } else {
        $users = DB::select("(
            SELECT a.id,a.auth_id
            FROM user_auth_types a
            WHERE a.id='$id') UNION
            (
            SELECT a.emp_no AS id,a.auth_id
            FROM emp_basic_details a
            WHERE a.emp_no='$id') UNION
            (
            SELECT a.id AS id,a.auth_id
            FROM users a
            WHERE a.id='$id')
            UNION
            (
            SELECT a.id AS id,a.auth_id
            FROM user_auth_types_extension a
            WHERE a.id='$id' AND a.status='A')");
    }
    $auth_array = array();
    foreach ($users as $key => $value) {
        array_push($auth_array, $value->auth_id);
    }

    return $auth_array;
}
function getReadNotification($limit = 10, $onlydata = false)
{
    if (Auth::check()) {
        $user_auth = getUserAuth(Auth::user()->id, true);
        $data['notifications'] = DB::table('notifications')
            ->where('user_from', Auth::user()->id)
            ->whereIn('auth_id', $user_auth)
            ->whereNotNull('read_at')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        // ->get();
        if ($onlydata) {
            return $data;
        } else {
            $data['readcount'] = getNotificationCount('read');
        }
    } else {
        return false;
    }
}
function getUnReadNotification($limit = 10, $onlydata = true)
{
    if (Auth::check()) {
        $user_auth = getUserAuths(Auth::user()->id, true);
        $notifications = DB::table('notifications')
            ->join('user_details', 'notifications.user_from', '=', 'user_details.id')
            ->select(DB::raw('notifications.*,CONCAT_WS(" ",user_details.first_name,user_details.middle_name,user_details.last_name) AS from_name,user_details.photopath,DATE_FORMAT(notifications.created_at, "%d-%m-%Y") AS format_date,
            if(CURDATE()=DATE_FORMAT(notifications.created_at, "%Y-%m-%d"),"Today",DATE_FORMAT(notifications.created_at, "%d-%m-%Y")) AS formated_date'))
            ->where('notifications.user_from', Auth::user()->id)
            ->whereIn('notifications.auth_id', $user_auth)
            ->whereNull('notifications.read_at')
            ->orderBy('notifications.created_at', 'desc')
            ->orderBy('notifications.read_at', 'desc')
            ->paginate($limit);
        // print_r($notifications);
        $data['notifications'] = $notifications;
        $data['unreadcount'] = getNotificationCount();
        if ($onlydata) {
            return $notifications;
        } else {
            return false;
        }
    } else {
        return "not";
    }
}
function getNotificationCount($type = 'unread')
{
    // $notifications =   DB::table('notifications')
    //     ->where('user_from', Auth::user()->id)
    //     ->whereNotNull('read_at')
    //     ->orderBy('created_at', 'desc')
    //     ->get();

    $query = DB::table('notifications')->where('user_from', Auth::user()->id);

    if ($type == 'read') {
        $query->whereNotNull('read_at');
    } else {
        $query->whereNull('read_at');
    }
    $result = $query->count();
    return $result;
}
function is_upload_dir_exists($dir)
{
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}
function validateAndSetFileName($fileName)
{
    $response = null;
    $fileName = str_replace(chr(0), '', $fileName);
    $fileName = str_replace('.php', '', $fileName);
    $fileName = str_replace('.sh', '', $fileName);
    $fileName = str_replace('00', '', $fileName);
    $fileName = str_replace(' ', '_', $fileName);
    $allowedExtensions = array('png', 'jpeg', 'jpg', 'pdf', 'csv', 'ics', 'icl', 'xlsx', 'xls', 'mp4', 'mov', 'avi', 'webm', 'wmv', 'm4v', 'flv');
    $file = pathinfo($fileName, PATHINFO_FILENAME);
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions)) {
        //do not upload file
        return false;
    } else {
        return $fileName;
    }
}
function validateFileContent($file, $fileName)
{
    $response['bError'] = false;
    $response['errorMsg'] = "";
    $imageExtensions = array('jpeg', 'png', 'jpg', 'pdf');
    $valid_mime_types = array(
        'png' => array("image/png", "image/jpeg", "image/jpg", "application/octet-stream"),
        'mp4' => array("video/mp4", "video/mov", "video/avi", "application/octet-stream", "video/webm", "video/wmv", "video/m4v", "video/flv"),
        'jpeg' => array("image/png", "image/jpeg", "image/jpg", "application/octet-stream"),
        'jpg' => array("image/png", "image/jpeg", "image/jpg", "application/octet-stream"),
        'pdf' => array("application/pdf", "application/octet-stream"),
        'csv' => array("text/plain", "application/octet-stream"),
        'ics' => array("text/calendar", "application/octet-stream"),
        'icl' => array("text/calendar", "application/octet-stream"),
        'xls' => array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/octet-stream", "application/vnd.ms-excel", "application/zip"),
        'xlsx' => array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/octet-stream", "application/vnd.ms-excel", "application/zip"),
    );
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (isset($valid_mime_types[$ext])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $mime = finfo_file($finfo, $file);
        finfo_close($finfo);
        if (!in_array($mime, $valid_mime_types[$ext])) {
            $response['bError'] = true;
            $response['errorMsg'] = "File extension mismatch";
        }
        if (in_array($ext, $imageExtensions)) {
            $imageSize = ($ext == 'pdf') ? filesize($file) : getimagesize($file);
            if (empty($imageSize)) {
                $response['bError'] = true;
                $response['errorMsg'] = "file not safe!";
            }
        }
    } else {
        $response['bError'] = true;
        $response['errorMsg'] = "Invalid extension file";
    }
    return $response;
}
function paginateArray($data, $perPage = 15)
{
    $page = Paginator::resolveCurrentPage();
    $total = count($data);
    $results = array_slice($data, ($page - 1) * $perPage, $perPage);

    return new LengthAwarePaginator($results, $total, $perPage, $page, [
        'path' => Paginator::resolveCurrentPath(),
    ]);
}

function getMenu()
{
    $user_auths = DB::table('user_auth_type')->select('auth_type')->where('status', 1)->where('user_id', Auth::user()->id)->groupBy('auth_type')->get();
    $menu = array();
    foreach ($user_auths as $i => $auth) {
        $menu[$auth->auth_type] = array();
        $model_menu = dyanmic_menu_gen($auth->auth_type);
        if (isset($model_menu[$auth->auth_type]) && is_array($model_menu[$auth->auth_type])) {
            $menu[$auth->auth_type] = array_merge($menu[$auth->auth_type], $model_menu[$auth->auth_type]);
        }
        if (file_exists(base_path() . "\app\Models\MenuModel.php")) {
            $menu[$auth->auth_type] = array();
            $MenuModel = new MenuModel();
            $model_menu = $MenuModel->getMenu();
            if (isset($model_menu[$auth->auth_type]) && is_array($model_menu[$auth->auth_type])) {
                $menu[$auth->auth_type] = array_merge($menu[$auth->auth_type], $model_menu[$auth->auth_type]);
            }
        }
    }
    return $menu;
}

function dyanmic_menu_gen($auth)
{

    $user_menu = DB::table('auth_menu_detail')->where("auth_id", $auth)->orderBy('auth_id', 'asc')->get();
    return get_dyanmic_menu($user_menu, $auth);
}
function get_dyanmic_menu($dmenu, $auth)
{
    $menu[$auth] = array();
    foreach ($dmenu as $d) {
        if ($d->submenu2 == null) {
            $menu[$auth][$d->submenu1] = url($d->link);
        } elseif ($d->submenu3 == null) {
            $menu[$auth][$d->submenu1][$d->submenu2] = url($d->link);
        } elseif ($d->submenu4 == null) {
            $menu[$auth][$d->submenu1][$d->submenu2][$d->submenu3] = url($d->link);
        } else {
            $menu[$auth][$d->submenu1][$d->submenu2][$d->submenu3][$d->submenu4] = url($d->link);
        }
    }
    return $menu;
}