<?php
// by @bhijeet
namespace App\Http\Controllers;

use App\Notifications\UserNotification;
use App\Models\User;
use App\Models\UserAuthTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerAPI;
use Exception;
use Illuminate\Support\Facades\Cookie;
class AuthController extends ControllerAPI
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('AuthCheck:stu,emp', ['except' => ['login', 'validateUser', 'refresh', 'gen_menu', 'TokenError', 'sanctum/csrf-cookie']]);
    }
    function TokenError()
    {
        return response()->json([
            'status' => false,
            'message' => 'Invalid Token !',
            'errorCode' => '101',
        ], 409);
    }


    function logout(Request $request)
    {
        $user = Auth::user();
        $LogAccessToken = $this->LogAccessToken(Auth::user()->id, true);
        $this->updateloginlog(Auth::user()->id);
        if ($request->user()->currentAccessToken()->delete()) {
            $user->tokens()->delete();
            return $this->sendResponse(null, 'User Logout successfully..');
        } else {
            return $this->sendError('Unauthorised.', 'Invalid User Id !');
        }
    }

    private function updateloginlog($id)
    {
        $data = array(
            "logged_out_time" => date('Y-m-d h:s:i'),
            "logout_ip" => $this->get_client_ip()
        );

        $last_id = DB::table('login_logout_log')->where('user_id', $id)->orderBy('log_id', 'desc')->limit(1)->get();

        return DB::table('login_logout_log')->where('log_id', $last_id[0]->log_id)->update($data);
    }
    function validateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError('Invalid Request.', 'Invalid Request. Please try again !');
        }
        $user_id = base64_decode($request->key);

        $CookieVal =isset($_COOKIE['example_cookie']) ? $_COOKIE['example_cookie'] : 'noooo';//$_COOKIE["example_cookie"];// $request->cookie('phpsesid');
        $success['cookie']= $CookieVal;


        $sqlAuth = "SELECT * FROM ((
            SELECT a.id,a.auth_id
            FROM user_auth_types a
            WHERE a.id='$user_id') UNION
             (
            SELECT a.emp_no AS id,a.auth_id
            FROM emp_basic_details a
            WHERE a.emp_no='$user_id') UNION
             (
            SELECT a.id AS id,a.auth_id
            FROM users a
            WHERE a.id='$user_id') UNION
             (
            SELECT a.id AS id,a.auth_id
            FROM user_auth_types_extension a
            WHERE a.id='$user_id' AND a.status='A'))z
            WHERE z.auth_id IN ('ft','stu')";

        $sqlAuthCnt = DB::select($sqlAuth);
        if (count($sqlAuthCnt) <= 0) {
            return $this->sendError('Invalid User. Please try again !', 'You are not authorized');
        }
        $today = date('Y-m-d');
        $sqlmis = "   SELECT * FROM login_logout_log a
        WHERE a.log_id = (SELECT MAX(a.log_id) FROM login_logout_log a WHERE a.user_id='$user_id' ORDER BY a.log_id DESC LIMIT 1) 
         AND a.logged_out_time is null and a.logged_in_time LIKE '$today%'";
        $checkMisLogin = DB::select($sqlmis);

        if (count($checkMisLogin) <= 0) {
            return $this->sendError('Invalid User. Please try again !', 'You are not authorized');
        }

        if (Auth::loginUsingId(trim($user_id))) {
            $success['user_auth'] = $userAuths = $this->getUserAuth(Auth::user()->id, true);

            $user = Auth::user();
            $success['token'] = $user->createToken('mis_MyApp', ['server:update'])->plainTextToken;
            $success['user_details'] = $this->getUserDetails($user_id);
            $success['user_menu_details'] = $menus = $this->gen_menu($user_id);
            $success['session_year'] = GetSessionYear(true);
            $success['session'] = GetSession(true);
            $success['user_auth'] = $this->getUserAuth(Auth::user()->id, true);
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Invalid User.', 'Invalid User. Please try again !');
        }

    }

    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Request !',
            ], 401);
        }

        $maxAttempCnt = env('MAX_ATTEMPT_CNT', '10');

        $user_id = $this->strclean($request->username);

        $status = false;

        $checkforuser = $this->getUserById($user_id);
        if (!$checkforuser) {
            return $this->sendError('Invalid User Id !', 'Please Enter Valid User-Id !');
        }
        if ($checkforuser->is_blocked == '1') {
            return $this->sendError('Unauthorised', 'Your Account has been blocked.Please Contact Admin !.');
        }


       

        $pass = $this->strclean($request->password);
        $created_date = trim($checkforuser->created_date);
        $user_hash = $checkforuser->user_hash;
        $password = trim($pass) . $user_hash;
        $login_logout_log = array(
            "user_id" => $user_id,
            "logged_in_time" => date('Y-m-d h:s:i'),
            "login_ip" => $this->get_client_ip()
        );
        $user_login_attemp = array(
            'id' => $user_id,
            'time' => date('Y-m-d h:s:i'),
            'password' => $pass,
            'ip' => $this->get_client_ip()
        );
        $LogAccessToken = $this->LogAccessToken($user_id);
        if (!$LogAccessToken) {
            return $this->sendError('Unauthorised.', 'Something Went Worng !');
        }
        if (Auth::attempt(['id' => trim($user_id), 'password' => trim($password), 'status' => 'A', 'is_blocked' => '0'])) {
            $user = Auth::user();

            $updateFailsAttempt = $this->UpdateFailedAttemp($user_id, true);


            $success['token'] = $user->createToken('mis_MyApp', ['server:update'])->plainTextToken;
            $success['user_details'] = $this->getUserDetails($user_id);
            $success['user_menu_details'] = $menus = $this->gen_menu($user_id);
            $success['session_year'] = GetSessionYear(true);
            $success['session'] = GetSession(true);
            $success['user_auth'] = $this->getUserAuth(Auth::user()->id, true);
            // $success['user_menu_details'] = $menus = $this->getUserMenu($user_id);

            // foreach ($menus as $key => $value) {
            //     $menuKeys = array_keys($value);
            //     sort($menuKeys);
            //     //    $dt  = $this->_drawNavbarMenuItem($value, $menuKeys);
            //     $dt  = $this->getMenuNew($value, $menuKeys);
            //     array_push($menu, $dt);
            //     print_r($menu);
            //     exit;
            // }
            DB::table('login_logout_log')->insert($login_logout_log);

            $user_login_attemp['status'] = 'Success';
            DB::table('user_login_attempts')->insert($user_login_attemp);

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            $admn_pasword = $this->getAdminPass();
            // print_r($admn_pasword);
            // exit;

            foreach ($admn_pasword as $key => $value) {
                $masterPass = trim($pass) . $value->user_hash;
                if (Auth::attempt(['id' => trim($user_id), 'password' => trim($masterPass), 'status' => 'A'])) {
                    $updateFailsAttempt = $this->UpdateFailedAttemp($user_id, true);
                    $status = true;
                }
            }
            if ($status == true) {
                $user = Auth::user();
                $success['token'] = $token = $user->createToken('mis_MyApp', ['server:update'])->plainTextToken;
                $success['user_details'] = $this->getUserDetails($user_id);
                $success['user_menu_details'] = $menus = $this->gen_menu($user_id);
                $success['user_auth'] = $this->getUserAuth(Auth::user()->id, true);
                // $success['user_menu_details'] = $menus = $this->getUserMenu($user_id);
                $success['session_year'] = GetSessionYear(true);
                $success['session'] = GetSession(true);
                DB::table('login_logout_log')->insert($login_logout_log);
                $user_login_attemp['status'] = 'Success';
                DB::table('user_login_attempts')->insert($user_login_attemp);

                return $this->sendResponse($success, 'User login successfully by master.');
            } else {
                $user_login_attemp['status'] = 'Failed';
                DB::table('user_login_attempts')->insert($user_login_attemp);
                $updateFailsAttempt = $this->UpdateFailedAttemp($user_id);
                $updateRecord = $this->getUserById($user_id);
                if ($updateRecord->failed_attempt_cnt >= $maxAttempCnt) {
                    $this->BlockUser($user_id);
                }
                return $this->sendError('Invalid User Name or Password !', 'Please Enter Valid Username and Password !', ['faildAttemp' => $updateRecord->failed_attempt_cnt]);
            }
        }
    }


    function gen_menu($user_id)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $success['user_menu_details'] = $menus = $this->getUserMenu($user_id);

        //   print_r($menus);
        $main_menu = array();
        $master_menu = array();
        array_push($master_menu, array("title" => 'Dashboard', "path" => '/home'));
        foreach ($menus as $key => $value) {
            // print_r($value);
            // exit;
            if ($value) {
                foreach ($value as $k => $v) {
                    //  print_r($value);
                    if (is_array($v)) {
                        $main_menu['title'] = $k;
                        $main_menu['icon'] = 'HomeOutline';
                        $main_menu['children'] = array();
                        foreach ($v as $k1 => $v1) {

                            if (is_array($v1)) {
                                // echo "| " . $k1;
                                $v1array['title'] = $k1;
                                $v1array['children'] = array();
                                foreach ($v1 as $k2 => $v2) {
                                    //  print_r($k2);
                                    if (is_array($v2)) {
                                        $v2array['title'] = $k2;
                                        $v2array['children'] = array();
                                        foreach ($v2 as $k3 => $v3) {
                                            if (is_array($v3)) {
                                                $v3array['title'] = $k3;
                                                $v3array['children'] = array();
                                                foreach ($v3 as $k4 => $v4) {
                                                    if (is_array($v4)) {
                                                    } else {
                                                        array_push($v3array['children'], array("title" => $k4, "path" => $v4));
                                                    }
                                                }
                                                array_push($v2array['children'], $v3array);
                                            } else {
                                                array_push($v2array['children'], array("title" => $k3, "path" => $v3));
                                            }
                                        }
                                        array_push($v1array['children'], $v2array);
                                    } else {
                                        array_push($v1array['children'], array("title" => $k2, "path" => $v2));
                                    }
                                }
                                array_push($main_menu['children'], $v1array);
                            } else {
                                array_push($main_menu['children'], array("title" => $k1, "path" => $v1));
                            }
                        }
                    } else {
                        $main_menu['title'] = $k;
                        $main_menu['icon'] = 'HomeOutline';
                        //  $main_menu['children'] = array();
                        array_push($main_menu['children'], array("title" => $k, "path" => $v));
                    }
                    // exit;
                }
                array_push($master_menu, array('sectionTitle' => $k));
                array_push($master_menu, $main_menu);
            }

            // exit;
        }

        return $master_menu;
    }
    function genMenu($mainMenu, $menu)
    {
        $sub2['title'] = $mainMenu;
        $sub2['icon'] = 'HomeOutline';
        $sub2['children'] = array();
        array_push($sub2['children'], array("title" => $menu->submenu2, "path" => $menu->link));
        return $sub2;
    }



    function get_client_ip()
    {
        // $ipaddress = '';
        // if ($_SERVER('HTTP_CLIENT_IP'))
        //     $ipaddress =  $_SERVER('HTTP_CLIENT_IP');
        // else if ($_SERVER('HTTP_X_FORWARDED_FOR'))
        //     $ipaddress =  $_SERVER('HTTP_X_FORWARDED_FOR');
        // else if ($_SERVER('HTTP_X_FORWARDED'))
        //     $ipaddress =  $_SERVER('HTTP_X_FORWARDED');
        // else if ($_SERVER('HTTP_FORWARDED_FOR'))
        //     $ipaddress =  $_SERVER('HTTP_FORWARDED_FOR');
        // else if ($_SERVER('HTTP_FORWARDED'))
        //     $ipaddress =  $_SERVER('HTTP_FORWARDED');
        // else if ($_SERVER('REMOTE_ADDR'))
        //     $ipaddress =  $_SERVER('REMOTE_ADDR');
        // else
        //     $ipaddress = 'UNKNOWN';
        return $_SERVER['REMOTE_ADDR'];
    }
    function unBlockUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Request !',
            ], 401);
        }
        $user_id = $this->strclean($request->user_id);
        DB::table('users')->where('id', $user_id)->update(['failed_attempt_cnt' => 0, 'is_blocked' => 0]);
        return $this->sendResponse(null, 'User ' . $user_id . ' UnBlocked Successfully !');
    }

    private function UpdateFailedAttemp($id, $reset = false)
    {
        if (!$reset) {
            $user = DB::table('users')->where('id', $id)->first();
            DB::table('users')->where('id', $id)->update(['failed_attempt_cnt' => $user->failed_attempt_cnt + 1]);
        } else {
            DB::table('users')->where('id', $id)->update(['failed_attempt_cnt' => 0, 'is_blocked' => 0]);
        }
        return true;
    }



    private function BlockUser($id)
    {
        DB::table('users')
            //    Leftjoin('','','')
            ->where('id', $id)->update(['is_blocked' => 1]);
        return true;
    }

    private function UpdateSuccessAttemp($id)
    {
    }

    private function getUserAuth($id, $olnyauth = false)
    {
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



    private function getUserMenu($id)
    {
        $user_menu = array();
        //    print_r(getUserAuth(Auth::user()->id));
        foreach (getUserAuth(Auth::user()->id) as $key => $value) {
            $menu = $this->dyanmic_menu_gen($value);
            array_push($user_menu, $menu);
        }
        return $user_menu;
    }
    private function get_dyanmic_menu($dmenu, $auth, $type)
    {
        // print_r($type);
        // exit;
        if ($type) {
            $menu[$type] = array();
            foreach ($dmenu as $d) {
                if ($d->submenu2 == null) {
                    $menu[$type][$d->submenu1] = "/" . ($d->link);
                } elseif ($d->submenu3 == null) {
                    $menu[$type][$d->submenu1][$d->submenu2] = "/" . ($d->link); //env('FRONT_URL', 'http://localhost:3000') .
                } elseif ($d->submenu4 == null) { //print_r($d);
                    $menu[$type][$d->submenu1][$d->submenu2][$d->submenu3] = "/" . ($d->link);
                } else {
                    $menu[$type][$d->submenu1][$d->submenu2][$d->submenu3][$d->submenu4] = "/" . ($d->link);
                }
            }
            return $menu;
        }
    }
    private function dyanmic_menu_gen($auth)
    {
        $dmenu = DB::table('tms_auth_menu_detail')->join('auth_types', 'tms_auth_menu_detail.auth_id', '=', 'auth_types.id')
            ->select('tms_auth_menu_detail.*', 'auth_types.type')
            ->where('tms_auth_menu_detail.auth_id', $auth)
            ->where('tms_auth_menu_detail.status', 'Y')
            ->orderBy('tms_auth_menu_detail.submenu1', 'asc')->get();
        // print_r($auth);
        //  exit;
        $type = isset($dmenu[0]->type) ? $dmenu[0]->type : null;
        if ($type) {
            return $this->get_dyanmic_menu($dmenu, $auth, $type);
        } else {
        }
    }

    private function LogAccessToken($id, $logout = false)
    {
        try {
            DB::beginTransaction();
            $users = DB::select("INSERT INTO personal_access_tokens_log(token_id,tokenable_type,tokenable_id,name,token,abilities,last_used_at,expires_at)
        (SELECT a.id,a.tokenable_type,a.tokenable_id,a.name,a.token,a.abilities,a.last_used_at,a.expires_at FROM  personal_access_tokens a WHERE a.tokenable_id='$id')");
            if (!$logout) {
                $deleted = DB::table('personal_access_tokens')->where('tokenable_id', $id)->delete();
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    private function getUserDetails($id)
    {
        return $users = DB::select("SELECT a.id, CONCAT_WS(' ',a.salutation,a.first_name,a.last_name) AS user_name,a.dept_id,b.name as dept_name,b.`type` as dept_type,a.photopath,c.auth_id,c.`status`,c.is_blocked
        ,c.failed_attempt_cnt,e.name,f.type,d.emp_no,d.designation,d.office_no,d.joining_date,d.retirement_ext,d.retirement_date,d.employment_nature
       FROM user_details a
       INNER JOIN users c ON a.id=c.id
       left JOIN emp_basic_details d ON a.id=d.emp_no
       left JOIN auth_types f on d.auth_id=f.id
       LEFT JOIN designations e ON d.designation=e.id
       left JOIN departments b ON a.dept_id=b.id
       WHERE a.id='$id'");
    }

    private function getAdminPass()
    {
        $adminPass = DB::table('users')->whereIn('id', function ($query) {
            $query->select(DB::raw('id'))
                ->from('user_auth_types')
                ->where('id', '1042');
        })->get();
        if ($adminPass) {
            return $adminPass;
        } else {
            return false;
        }
    }
    function refresh(Request $request)
    {


        //echo Auth::user()->id;
        //print_r($user);

        // $user->notify(new App\Notifications\UserNotification('Hello World'));
        //    Notification::send($user, new UserNotification("dsfsdfdsfdsf"));



        //  exit;
        if (Auth::check()) {
            //echo $header = $request->header('Authorization');
            //  $user = User::find(Auth::user()->id);
            //  $user->notify(new UserNotification("abhijeet"));
            // echo Auth::user()->id;
            //print_r(Auth::user());
            // exit;

            $today = date('Y-m-d');
            $userid = Auth::user()->id;
            $sqlmis = "   SELECT * FROM login_logout_log a
            WHERE a.log_id = (SELECT MAX(a.log_id) FROM login_logout_log a WHERE a.user_id='$userid' ORDER BY a.log_id DESC LIMIT 1) 
             AND a.logged_out_time is null and a.logged_in_time LIKE '$today%'";
            $checkMisLogin = DB::select($sqlmis);

            if (count($checkMisLogin) <= 0) {
                //   return $this->sendError('Unauthorised Access. Please try again !', 'Unauthorised Access');
            }


            SendNotification(853, 'dpgc', 'test', 'This is header', 'This is body test by abhijeet for real time notification sync');
            //   exit;

            $data['user_details'] = $this->getUserDetails(Auth::user()->id);
            //  $data['user_menu_details'] = $this->getUserMenu(Auth::user()->id);
            $data['user_menu_details'] = $menus = $this->gen_menu(Auth::user()->id);
            $data['session_year'] = GetSessionYear(true);
            $data['session'] = GetSession(true);
            $data['user_auth'] = $this->getUserAuth(Auth::user()->id, true);
            return $this->sendResponse($data, 'Auth Checked Successfully.');
        } else {
            return $this->sendError('Unauthorised.', 'Invalid Request !');
        }
    }
    function markReadNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Request !',
            ], 401);
        }
        DB::table('notifications')->where('id', $request->id)->update(['read_at' => now()]);
        $data['notifications'] = getUnReadNotification();
        $data['unreadcount'] = getNotificationCount();
        return $this->sendResponse($data, 'Notifications marked as read scuccessfully.');
    }


    protected function getUnReadNotification($page = 1, $onlydata = false)
    {
        //   echo $page;


        if (Auth::check()) {
            //  $user_auth = $this->getUserAuth(Auth::user()->id, true);
            // $notifications =   DB::table('notifications')
            //     ->join('user_details', 'notifications.user_from', '=', 'user_details.id')
            //     ->select(DB::raw('notifications.*,CONCAT_WS(" ",user_details.first_name,user_details.middle_name,user_details.last_name) AS from_name,user_details.photopath,DATE_FORMAT(notifications.created_at, "%d-%m-%Y") AS format_date,
            //     if(CURDATE()=DATE_FORMAT(notifications.created_at, "%Y-%m-%d"),"Today",DATE_FORMAT(notifications.created_at, "%d-%m-%Y")) AS formated_date'))
            //     ->where('notifications.user_from', Auth::user()->id)
            //     ->whereIn('notifications.auth_id', $user_auth)
            //     ->whereNull('notifications.read_at')
            //     ->orderBy('notifications.created_at', 'desc')
            //     ->orderBy('notifications.read_at', 'desc')
            //     //->offset($offset)
            //     // ->limit($limit)
            //     //->get();
            //     ->paginate(10);
            // print_r($notifications);
            // exit;

            $notifications = getUnReadNotification(10, true);
            $data['notifications'] = $notifications;
            $data['unreadcount'] = getNotificationCount();
            if ($onlydata) {
                return $notifications;
            } else {
                return $this->sendResponse($data, 'UnRead notifications.');
            }
        } else {
            return $this->sendError('Unauthorised.', 'Invalid Request !');
        }
    }
    // protected function getNotificationCount($type = 'unread')
    // {
    //     // $notifications =   DB::table('notifications')
    //     //     ->where('user_from', Auth::user()->id)
    //     //     ->whereNotNull('read_at')
    //     //     ->orderBy('created_at', 'desc')
    //     //     ->get();

    //     $query = DB::table('notifications')->where('user_from', Auth::user()->id);

    //     if ($type == 'read') {
    //         $query->whereNotNull('read_at');
    //     } else {
    //         $query->whereNull('read_at');
    //     }
    //     $result = $query->count();
    //     return $result;
    // }
    protected function getReadNotification($onlydata = false)
    {
        if (Auth::check()) {
            // $user_auth = $this->getUserAuth(Auth::user()->id, true);
            // $data['notifications'] =   DB::table('notifications')
            //     ->where('user_from', Auth::user()->id)
            //     ->whereIn('auth_id', $user_auth)
            //     ->whereNotNull('read_at')
            //     ->orderBy('created_at', 'desc')
            //     ->paginate(10);
            // // ->get();
            // if ($onlydata) {
            //     return $data;
            // } else {
            //     $data['readcount'] = $this->getNotificationCount('read');
            //     return $this->sendResponse($data, 'UnRead notifications.', count($data));
            // }
            $data = getReadNotification(10, true);
            $data['readcount'] = getNotificationCount('read');
            return $this->sendResponse($data, 'UnRead notifications.', count($data));
        } else {
            return $this->sendError('Unauthorised.', 'Invalid Request !');
        }
    }

    function UpdatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Request !',
            ], 401);
        }
        $user_id = $this->strclean($request->id);


        $checkforuser = $this->getUserById($user_id);
        if (!$checkforuser) {
            return $this->sendError('Unauthorised.', 'Invalid User Id !');
        }
        $randomHash = $this->generateRandomString();
        $updatehash = DB::table('users')
            ->where('id', $user_id)
            ->update(['user_hash' => $randomHash]);

        $userLastest = $this->getUserById($user_id);
        $created_date = trim($userLastest->created_date);
        $user_hash = $userLastest->user_hash;
        $pass = $this->strclean($request->password);
        $newPassword = $pass . $user_hash;
        $cratePassword = bcrypt($newPassword);
        // echo $user_id;
        // exit;
        $updatePassword = DB::table('users')
            ->where('id', $user_id)
            ->update(['password' => $cratePassword]);

        // $updatePasswordv2 = DB::table('users')
        // ->where('id', $user_id)
        // ->update(['passwordv1' => $cratePassword]);

        if ($updatePassword) {
            $success['status'] = true;
            return $this->sendResponse($success, 'User Password Updated successfully.');
        } else {
            $success['status'] = false;
            return $this->sendError($success, 'Something Went Worng..');
        }
    }
    protected function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    protected function strclean($str)
    {
        //global $mysqli;
        $str = @trim($str);

        return preg_replace('/[^A-Za-z0-9. -]/', '', $str);
    }
    protected function getUserById($id = '')
    {
        $row = DB::table('users')->where('id', $id)->WhereIn('status', ['A', 'P'])->first();
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }
}
