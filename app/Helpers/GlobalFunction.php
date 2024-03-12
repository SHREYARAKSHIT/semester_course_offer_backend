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