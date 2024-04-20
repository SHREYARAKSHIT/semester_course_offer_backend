<?php

namespace App\Http\Controllers;

//use App\Notifications\UserNotification;
//use App\Models\User;
//use App\Models\UserAuthTypes;
//use Illuminate\Support\Facades\Validator;
//use App\Http\Controllers\Controller;
//use Exception;
//use App\Http\Controllers\AuthController;
//use Illuminate\Support\Facades\Cookie;

use App\Http\Controllers\ControllerAPI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessController extends ControllerAPI
{
    public function gettwoseparatecourse(Request $request){
        $twoseparatecourse = explode('/', $request->formData['course_category']);
        return $this->sendResponse($twoseparatecourse, 'Success..');
    }
    public function getsessionyear(Request $request){
        $sessionyear=DB::table('mis_session_year')->select(DB::raw('session_year'))->get();
        return $this->sendResponse($sessionyear, 'Success..');
    }
    public function getsession(Request $request){
        $session=DB::table('mis_session')->select(DB::raw('session'))->get();
        return $this->sendResponse($session, 'Success..');
    }
    public function getcoursee(Request $request){
        $coursee=DB::table('cbcs_courses')->select(DB::raw('name'))->where('status',1)->get();
        return $this->sendResponse($coursee, 'Success..');
    }
    public function getbranchee(Request $request){
        $branchee=[];
        $courseeid=DB::table('cbcs_courses')->where('name',$request['course'])->where('status',1)->value('id');
        $brancheeid=DB::table('course_branch')->select('branch_id')->where('course_id',$courseeid)->pluck('branch_id');
        /*foreach($branchee as $item){
            $it=DB::table('cbcs_branches')->where('id',$item->branch_id)->where('status',1)->value('name');
            $item->name = $it;
        }*/
        $branche=DB::table('cbcs_branches')->select('name')->whereIn('id',$brancheeid)->where('status',1)->distinct()->pluck('name');
        foreach ($branche as $it) {
            $branchee[] = ['name' => $it];
        }

        return $this->sendResponse($branchee, 'Success..');
    }
    public function getdepartment(Request $request){
        /*$department=[];
        $courseId = DB::table('cbcs_courses')->where('name',$request['course'])->where('status',1)->value('id');
        $branchId = DB::table('cbcs_branches')->where('name', $request['branch'])->where('status',1)->value('id');
        $course_branch_id = DB::table('course_branch')->select(DB::raw('course_branch_id'))->where('course_id',$courseId)->where('branch_id',$branchId)->get();
        foreach($course_branch_id as $item){
            $dept_code_list = DB::table('dept_course')->select(DB::raw('dept_id'))->where('course_branch_id',$item->course_branch_id)->get();
            foreach($dept_code_list as $item2){
            $list=DB::table('cbcs_departments')->select(DB::raw('name'))->where('id',$item2->dept_id)->where('type','academic')->where('status',1)->get();
            //add $list to department
            $department[] = $list;}
        }
        return $this->sendResponse($department, 'Success..');*/
        $department = [];

        // Retrieve the course ID
        $courseId = DB::table('cbcs_courses')->where('name', $request['course'])->where('status', 1)->value('id');

        // Retrieve the branch ID
        $branchId = DB::table('cbcs_branches')->where('name', $request['branch'])->where('status', 1)->value('id');

        // Retrieve the course branch IDs based on the course and branch
        $course_branch_ids = DB::table('course_branch')
            ->select('course_branch_id')
            ->where('course_id', $courseId)
            ->where('branch_id', $branchId)
            ->pluck('course_branch_id');

        // Retrieve the department IDs associated with the course branches
        $dept_ids = DB::table('dept_course')
            ->whereIn('course_branch_id', $course_branch_ids)
            ->pluck('dept_id');

        // Retrieve the names of the academic departments with status 1 associated with the department IDs
        $departments = DB::table('cbcs_departments')
            ->select('name')
            ->whereIn('id', $dept_ids)
            ->where('type', 'academic')
            ->where('status', 1)
            ->distinct() // Ensure uniqueness
            ->pluck('name');

        // Add the unique department names to the $department array
        foreach ($departments as $name) {
            $department[] = ['name' => $name];
        }

        return $this->sendResponse($department, 'Success..');

    }
    public function coursedata(Request $request)
    {
        // Step 1
        //->select(DB::raw('id'))->where
        if($request->formData['session_year']===null || $request->formData['session']===null || $request->formData['course']===null || $request->formData['branch']===null || $request->formData['department']===null || $request->formData['batch']===null){return $this->sendError('Failed','All fields are mandatory!');}

        $courseId = DB::table('cbcs_courses')->where('name',$request->formData['course'])->where('status',1)->value('id');

        // Step 2
        $branchId = DB::table('cbcs_branches')->where('name', $request->formData['branch'])->where('status',1)->value('id');

        $departmentId = DB::table('cbcs_departments')->where('name', $request->formData['department'])->where('status',1)->value('id');

        // Step 3
        if ($request->formData['branch'] === 'Common Branch for 1st Year') {
            if($request->formData['section']===null){return $this->sendError('Failed','All fields are mandatory!');}
            if ($request->formData['section']=== 'ABCD') {
                $policyId = '1';
            } elseif ($request->formData['section']=== 'EFGH') {
                $policyId = '2';
            } else {
                $policyId = '3';
            }
        } else {
            // Policy ID logic if branch is not Common Branch for 1st Year
            $policyId = DB::table('cbcs_credit_points_policy')
                //->select(DB::raw('id'))
                ->where('wef','<=' ,$request->formData['batch'])
                ->where('course_id', $courseId)
                ->orderBy('wef', 'desc')
                ->limit(1)
                ->value('id');
        }


        // Step 5
        if ($request->formData['branch'] === 'Common Branch for 1st Year') {
            $a = DB::table('cbcs_comm_coursestructure_policy')
                //->select('course_component', 'sequence', 'status')
                ->select(DB::raw('course_component'))
                ->addSelect(DB::raw('sequence'))
                ->addSelect(DB::raw('status'))
                ->where('course_id', $courseId)
                ->where('sem', $request->formData['semester'])
                ->where('cbcs_curriculam_policy_id', $policyId)
                ->get();
        } else {
            $a = DB::table('cbcs_coursestructure_policy')
                //->select('course_component', 'sequence', 'status')
                ->select(DB::raw('course_component'))
                ->addSelect(DB::raw('sequence'))
                ->addSelect(DB::raw('status'))
                ->where('course_id', $courseId)
                ->where('sem', $request->formData['semester'])
                ->where('cbcs_curriculam_policy_id', $policyId)
                ->get();
        }

        // Step 6
    foreach ($a as $item) {
        if (strpos($item->course_component, '/') !== false && strpos($item->sequence, '/') !== false) {
            // Extracting components from course_component and sequence
            $courseComponents = explode('/', $item->course_component);
            $sequenceComponents = explode('/', $item->sequence);

            // Constructing the modified sequence
            // Updating the sequence in the current item
            $item->sequence = $courseComponents[0] . $sequenceComponents[0] . '/' . $courseComponents[1] . $sequenceComponents[1];
        }
        else{
            $item->sequence = $item->course_component. $item->sequence;
        }
    }



    // Step 7
    if($request->formData['branch'] === 'Common Branch for 1st Year'){
        $b = DB::table('cbcs_subject_offered')
        ->select('sub_category', 'unique_sub_pool_id', 'sub_code', 'sub_name')
        //->select(DB::raw('course_component'))
        //->addSelect(DB::raw('sequence'))
        ->where('session_year', $request->formData['session_year'])
        ->where('session', $request->formData['session'])
        ->where('course_id', $courseId)
        ->where('branch_id', $branchId)
        ->where('dept_id', $departmentId)
        ->where('semester', $request->formData['semester'])
        ->where('sub_group', $policyId)
        ->get();
    }else{
        $b = DB::table('cbcs_subject_offered')
        ->select('sub_category', 'unique_sub_pool_id', 'sub_code', 'sub_name')
        ->where('session_year', $request->formData['session_year'])
        ->where('session', $request->formData['session'])
        ->where('course_id', $courseId)
        ->where('branch_id', $branchId)
        ->where('dept_id', $departmentId)
        ->where('semester', $request->formData['semester'])
        ->get();
    }

    foreach ($b as $item) {
        if ($item->unique_sub_pool_id==='NA'||$item->unique_sub_pool_id==='') {
            $item->unique_sub_pool_id=$item->sub_category;
        }
    }
    foreach ($a as $item) {
        $flag=0;
        foreach($b as $item2){
            if($item->sequence===$item2->unique_sub_pool_id){
                $item->sub_category=$item2->sub_category;
                $item->sub_code=$item2->sub_code;
                $item->sub_name=$item2->sub_name;
                $item->offer_condition=1;
                $flag=1;
                break;
            }
        } 
        if($flag===0){
            $item->sub_category="";
            $item->sub_code="";
            $item->sub_name="";
        }
    }
        return $this->sendResponse($a, 'Success..');
    }
    public function getsubname(Request $request){
        $departmentId = DB::table('cbcs_departments')->where('name', $request['department'])->where('status',1)->value('id');
        $subname = DB::table('cbcs_course_master')->select('sub_name','sub_code')->where('dept_id',$departmentId)->distinct()->get();
        return $this->sendResponse($subname, 'Success..');
    }
    public function getsubdetail(Request $request){
        $departmentId = DB::table('cbcs_departments')->where('name', $request['department'])->where('status',1)->value('id');
        $subdet = DB::table('cbcs_course_master')->where('dept_id',$departmentId)->where('sub_name',$request['subject_name'])->where('sub_code',$request['subject_code'])->distinct()->get();
        return $this->sendResponse($subdet, 'Success..');
    }
    public function getdeptoffaculty(Request $request){
        $deptoffaculty = DB::table('cbcs_departments')->select('id','name')->where('type', 'academic')->where('status',1)->get();
        return $this->sendResponse($deptoffaculty, 'Success..');
    }
    public function sub1(Request $request){
        $dept_id = DB::table('cbcs_departments')->where('name', $request->data1['department'])->where('status',1)->value('id');
        $course_id = DB::table('cbcs_courses')->where('name',$request->data1['course'])->where('status',1)->value('id');
        $branch_id = DB::table('cbcs_branches')->where('name', $request->data1['branch'])->where('status',1)->value('id');
        if(strpos($request->data1['course_component'], '/') !== false && strpos($request->data1['course_category'], '/') !== false){
            $unique_sub_pool_id=$request->data1['course_category'];
            $sub_category=$request->data1['selected_course_category'];
        }else{
            $unique_sub_pool_id='NA';
            $sub_category=$request->data1['course_category'];
        }
        $checking = DB::table('cbcs_subject_offered')->where('dept_id', $dept_id)->where('course_id',$course_id)->where('branch_id',$branch_id)->where('semester',$request->data1['semester'])->where('sub_name',$request->data1['subject_name'])->where('sub_code',$request->data1['subject_code'])->where('lecture',$request->data1['lecture'])->where('tutorial',$request->data1['tutorial'])->where('practical',$request->data1['practical'])->where('sub_category' , $sub_category)->where('unique_sub_pool_id' , $unique_sub_pool_id)->get();
        if (!$checking->isEmpty()){$wef_year=$checking[0]->wef_year; $wef_session=$checking[0]->wef_session;}
        else{$wef_year=$request->data1['session_year']; $wef_session=$request->data1['session'];}
        
        $check2 = DB::table('cbcs_subject_offered')
            ->where('dept_id',$dept_id)
            ->where('course_id' , $course_id)
            ->where('branch_id' , $branch_id)
            ->where('semester' , $request->data1['semester'])
            ->where('unique_sub_pool_id' , $unique_sub_pool_id)
            ->where('unique_sub_id' , 'NA')
            ->where('sub_name' , $request->data1['subject_name'])
            ->where('sub_code' , $request->data1['subject_code'])
            ->where('lecture' , $request->data1['lecture'])
            ->where('tutorial' , $request->data1['tutorial'])
            ->where('practical' , $request->data1['practical'])
            ->where('credit_hours' , $request->data1['credit_hours'])
            ->where('contact_hours' , $request->data1['contact_hours'])
            ->where('sub_type' , $request->data1['subject_type'])
            ->where('wef_year' , $wef_year)
            ->where('wef_session', $wef_session)
            ->where('pre_requisite' , $request->data1['prerequisite'])
            ->where('pre_requisite_subcode' , $request->data1['prerequisite_sub_code'])
            ->where('fullmarks' , $request->data1['full_marks'])
            ->where('no_of_subjects' , $request->data1['no_of_part'])
            ->where('sub_category' , $sub_category)
            ->where('sub_group' , '0')
            ->where('criteria' , $request->data1['criteria'])
            ->where('minstu' , $request->data1['min_stu'])
            ->where('maxstu' , $request->data1['max_stu'])
            ->get();
        if (!$check2->isEmpty()){$ac='copy';}else{$ac='insert';}

        DB::table('cbcs_subject_offered')->insert([
            'session_year' => $request->data1['session_year'],
            'session' => $request->data1['session'],
            'dept_id' => $dept_id,
            'course_id' => $course_id,
            'branch_id' => $branch_id,
            'semester' => $request->data1['semester'],
            'unique_sub_pool_id' => $unique_sub_pool_id,
            'unique_sub_id' => 'NA',
            'sub_name' => $request->data1['subject_name'],
            'sub_code' => $request->data1['subject_code'],
            'lecture' => $request->data1['lecture'],
            'tutorial' => $request->data1['tutorial'],
            'practical' => $request->data1['practical'],
            'credit_hours' => $request->data1['credit_hours'],
            'contact_hours' => $request->data1['contact_hours'],
            'sub_type' => $request->data1['subject_type'],
            'wef_year' => $wef_year,
            'wef_session' => $wef_session,
            'pre_requisite' => $request->data1['prerequisite'],
            'pre_requisite_subcode' => $request->data1['prerequisite_sub_code'],
            'fullmarks' => $request->data1['full_marks'],
            'no_of_subjects' => $request->data1['no_of_part'],
            'sub_category' => $sub_category,
            'sub_group' => '0',
            'criteria' => $request->data1['criteria'],
            'minstu' => $request->data1['min_stu'],
            'maxstu' => $request->data1['max_stu'],
            'remarks' => $request->data1['remarks'],
            'created_by' => Auth::user()->id,//need to be updated
            'action' => $ac
        ]);
        $c_id=DB::table('cbcs_subject_offered')
        ->where('session_year', $request->data1['session_year'])
        ->where('session', $request->data1['session'])
        ->where('dept_id',$dept_id)
        ->where('course_id' , $course_id)
        ->where('branch_id' , $branch_id)
        ->where('semester' , $request->data1['semester'])
        ->where('unique_sub_pool_id' , $unique_sub_pool_id)
        ->where('unique_sub_id' , 'NA')
        ->where('sub_name' , $request->data1['subject_name'])
        ->where('sub_code' , $request->data1['subject_code'])
        ->where('lecture' , $request->data1['lecture'])
        ->where('tutorial' , $request->data1['tutorial'])
        ->where('practical' , $request->data1['practical'])
        ->where('credit_hours' , $request->data1['credit_hours'])
        ->where('contact_hours' , $request->data1['contact_hours'])
        ->where('sub_type' , $request->data1['subject_type'])
        ->where('wef_year' , $wef_year)
        ->where('wef_session', $wef_session)
        ->where('pre_requisite' , $request->data1['prerequisite'])
        ->where('pre_requisite_subcode' , $request->data1['prerequisite_sub_code'])
        ->where('fullmarks' , $request->data1['full_marks'])
        ->where('no_of_subjects' , $request->data1['no_of_part'])
        ->where('sub_category' , $sub_category)
        ->where('sub_group' , '0')
        ->where('criteria' , $request->data1['criteria'])
        ->where('minstu' , $request->data1['min_stu'])
        ->where('maxstu' , $request->data1['max_stu'])
        ->where('remarks' , $request->data1['remarks'])
        ->where('created_by' , Auth::user()->id)
        ->where('action' , $ac)
        ->value('id');
        foreach($request->data2 as $item){
            DB::table('cbcs_subject_offered_desc')->insert([
                'sub_offered_id' => $c_id,
                'part' => $item['part'],
                'emp_no' => $item['faculty'],
                'coordinator' => $item['marks_upload_right'],
                'sub_id' => $request->data1['subject_code'],
                'section' => '',
            ]);
        }
        return $this->sendResponse('Success', 'Success..');
    }
    public function sub8(Request $request){
        $dept_id = DB::table('cbcs_departments')->where('name', $request->data1['department'])->where('status',1)->value('id');
        $course_id = DB::table('cbcs_courses')->where('name',$request->data1['course'])->where('status',1)->value('id');
        $branch_id = DB::table('cbcs_branches')->where('name', $request->data1['branch'])->where('status',1)->value('id');
        if(strpos($request->data1['course_component'], '/') !== false && strpos($request->data1['course_category'], '/') !== false){
            $unique_sub_pool_id=$request->data1['course_category'];
            $sub_category=$request->data1['selected_course_category'];
        }else{
            $unique_sub_pool_id='NA';
            $sub_category=$request->data1['course_category'];
        }
        $checking = DB::table('cbcs_subject_offered')->where('dept_id', $dept_id)->where('course_id',$course_id)->where('branch_id',$branch_id)->where('semester',$request->data1['semester'])->where('sub_name',$request->data1['subject_name'])->where('sub_code',$request->data1['subject_code'])->where('lecture',$request->data1['lecture'])->where('tutorial',$request->data1['tutorial'])->where('practical',$request->data1['practical'])->where('sub_category' , $sub_category)->where('unique_sub_pool_id' , $unique_sub_pool_id)->get();
        if (!$checking->isEmpty()){$wef_year=$checking[0]->wef_year; $wef_session=$checking[0]->wef_session;}
        else{$wef_year=$request->data1['session_year']; $wef_session=$request->data1['session'];}
        
        $check2 = DB::table('cbcs_subject_offered')
            ->where('dept_id',$dept_id)
            ->where('course_id' , $course_id)
            ->where('branch_id' , $branch_id)
            ->where('semester' , $request->data1['semester'])
            ->where('unique_sub_pool_id' , $unique_sub_pool_id)
            ->where('unique_sub_id' , 'NA')
            ->where('sub_name' , $request->data1['subject_name'])
            ->where('sub_code' , $request->data1['subject_code'])
            ->where('lecture' , $request->data1['lecture'])
            ->where('tutorial' , $request->data1['tutorial'])
            ->where('practical' , $request->data1['practical'])
            ->where('credit_hours' , $request->data1['credit_hours'])
            ->where('contact_hours' , $request->data1['contact_hours'])
            ->where('sub_type' , $request->data1['subject_type'])
            ->where('wef_year' , $wef_year)
            ->where('wef_session', $wef_session)
            ->where('pre_requisite' , $request->data1['prerequisite'])
            ->where('pre_requisite_subcode' , $request->data1['prerequisite_sub_code'])
            ->where('fullmarks' , $request->data1['full_marks'])
            ->where('no_of_subjects' , $request->data1['no_of_part'])
            ->where('sub_category' , $sub_category)
            ->where('sub_group' , '0')
            ->where('criteria' , $request->data1['criteria'])
            ->where('minstu' , $request->data1['min_stu'])
            ->where('maxstu' , $request->data1['max_stu'])
            ->get();
        if (!$check2->isEmpty()){$ac='copy';}else{$ac='insert';}

        DB::table('cbcs_subject_offered')->insert([
            'session_year' => $request->data1['session_year'],
            'session' => $request->data1['session'],
            'dept_id' => $dept_id,
            'course_id' => $course_id,
            'branch_id' => $branch_id,
            'semester' => $request->data1['semester'],
            'unique_sub_pool_id' => $unique_sub_pool_id,
            'unique_sub_id' => 'NA',
            'sub_name' => $request->data1['subject_name'],
            'sub_code' => $request->data1['subject_code'],
            'lecture' => $request->data1['lecture'],
            'tutorial' => $request->data1['tutorial'],
            'practical' => $request->data1['practical'],
            'credit_hours' => $request->data1['credit_hours'],
            'contact_hours' => $request->data1['contact_hours'],
            'sub_type' => $request->data1['subject_type'],
            'wef_year' => $wef_year,
            'wef_session' => $wef_session,
            'pre_requisite' => $request->data1['prerequisite'],
            'pre_requisite_subcode' => $request->data1['prerequisite_sub_code'],
            'fullmarks' => $request->data1['full_marks'],
            'no_of_subjects' => $request->data1['no_of_part'],
            'sub_category' => $sub_category,
            'sub_group' => '0',
            'criteria' => $request->data1['criteria'],
            'minstu' => $request->data1['min_stu'],
            'maxstu' => $request->data1['max_stu'],
            'remarks' => $request->data1['remarks'],
            'created_by' => Auth::user()->id,//need to be updated
            'action' => $ac
        ]);
        $c_id=DB::table('cbcs_subject_offered')
        ->where('session_year', $request->data1['session_year'])
        ->where('session', $request->data1['session'])
        ->where('dept_id',$dept_id)
        ->where('course_id' , $course_id)
        ->where('branch_id' , $branch_id)
        ->where('semester' , $request->data1['semester'])
        ->where('unique_sub_pool_id' , $unique_sub_pool_id)
        ->where('unique_sub_id' , 'NA')
        ->where('sub_name' , $request->data1['subject_name'])
        ->where('sub_code' , $request->data1['subject_code'])
        ->where('lecture' , $request->data1['lecture'])
        ->where('tutorial' , $request->data1['tutorial'])
        ->where('practical' , $request->data1['practical'])
        ->where('credit_hours' , $request->data1['credit_hours'])
        ->where('contact_hours' , $request->data1['contact_hours'])
        ->where('sub_type' , $request->data1['subject_type'])
        ->where('wef_year' , $wef_year)
        ->where('wef_session', $wef_session)
        ->where('pre_requisite' , $request->data1['prerequisite'])
        ->where('pre_requisite_subcode' , $request->data1['prerequisite_sub_code'])
        ->where('fullmarks' , $request->data1['full_marks'])
        ->where('no_of_subjects' , $request->data1['no_of_part'])
        ->where('sub_category' , $sub_category)
        ->where('sub_group' , '0')
        ->where('criteria' , $request->data1['criteria'])
        ->where('minstu' , $request->data1['min_stu'])
        ->where('maxstu' , $request->data1['max_stu'])
        ->where('remarks' , $request->data1['remarks'])
        ->where('created_by' , Auth::user()->id)
        ->where('action' , $ac)
        ->value('id');
        foreach($request->data2 as $item){
            DB::table('cbcs_subject_offered_desc')->insert([
                'sub_offered_id' => $c_id,
                'part' => $item['part'],
                'emp_no' => $item['faculty'],
                'coordinator' => $item['marks_upload_right'],
                'sub_id' => $request->data1['subject_code'],
                'section' => '',
            ]);
        }
        if($request->data1['same_branch_opt_status']===true){$same_branch_opt_status='Yes';}else{$same_branch_opt_status='No';}
        DB::table('cbcs_subject_offered_same_branch_status')->insert([
            'sub_offered_id' => $c_id,
            'same_branch_opt_status' => $same_branch_opt_status,
        ]);
        return $this->sendResponse('Success', 'Success..');
    }
    public function sub10(Request $request){
        $dept_id = DB::table('cbcs_departments')->where('name', $request->data1['department'])->where('status',1)->value('id');
        $course_id = DB::table('cbcs_courses')->where('name',$request->data1['course'])->where('status',1)->value('id');
        $branch_id = DB::table('cbcs_branches')->where('name', $request->data1['branch'])->where('status',1)->value('id');
        if(strpos($request->data1['course_component'], '/') !== false && strpos($request->data1['course_category'], '/') !== false){
            $unique_sub_pool_id=$request->data1['course_category'];
            $sub_category=$request->data1['selected_course_category'];
        }else{
            $unique_sub_pool_id='NA';
            $sub_category=$request->data1['course_category'];
        }
        if($request->data1['section']==='ABCD'){$sub_group='1';}
        else if($request->data1['section']==='EFGH'){$sub_group='2';}

        $checking = DB::table('cbcs_subject_offered')->where('dept_id', $dept_id)->where('course_id',$course_id)->where('branch_id',$branch_id)->where('semester',$request->data1['semester'])->where('sub_name',$request->data1['subject_name'])->where('sub_code',$request->data1['subject_code'])->where('lecture',$request->data1['lecture'])->where('tutorial',$request->data1['tutorial'])->where('practical',$request->data1['practical'])->where('sub_group',$sub_group)->where('unique_sub_pool_id' , $unique_sub_pool_id)->where('sub_category' , $sub_category)->get();
        if (!$checking->isEmpty()){$wef_year=$checking[0]->wef_year; $wef_session=$checking[0]->wef_session;}
        else{$wef_year=$request->data1['session_year']; $wef_session=$request->data1['session'];}
        
        $check2 = DB::table('cbcs_subject_offered')
            ->where('dept_id',$dept_id)
            ->where('course_id' , $course_id)
            ->where('branch_id' , $branch_id)
            ->where('semester' , $request->data1['semester'])
            ->where('unique_sub_pool_id' , $unique_sub_pool_id)
            ->where('unique_sub_id' , 'NA')
            ->where('sub_name' , $request->data1['subject_name'])
            ->where('sub_code' , $request->data1['subject_code'])
            ->where('lecture' , $request->data1['lecture'])
            ->where('tutorial' , $request->data1['tutorial'])
            ->where('practical' , $request->data1['practical'])
            ->where('credit_hours' , $request->data1['credit_hours'])
            ->where('contact_hours' , $request->data1['contact_hours'])
            ->where('sub_type' , $request->data1['subject_type'])
            ->where('wef_year' , $wef_year)
            ->where('wef_session', $wef_session)
            ->where('pre_requisite' , $request->data1['prerequisite'])
            ->where('pre_requisite_subcode' , $request->data1['prerequisite_sub_code'])
            ->where('fullmarks' , $request->data1['full_marks'])
            ->where('no_of_subjects' , $request->data1['no_of_part'])
            ->where('sub_category' , $sub_category)
            ->where('sub_group' , $sub_group)
            ->where('criteria' , $request->data1['criteria'])
            ->where('minstu' , $request->data1['min_stu'])
            ->where('maxstu' , $request->data1['max_stu'])
            ->get();
        if (!$check2->isEmpty()){$ac='copy';}else{$ac='insert';}

        DB::table('cbcs_subject_offered')->insert([
            'session_year' => $request->data1['session_year'],
            'session' => $request->data1['session'],
            'dept_id' => $dept_id,
            'course_id' => $course_id,
            'branch_id' => $branch_id,
            'semester' => $request->data1['semester'],
            'unique_sub_pool_id' => $unique_sub_pool_id,
            'unique_sub_id' => 'NA',
            'sub_name' => $request->data1['subject_name'],
            'sub_code' => $request->data1['subject_code'],
            'lecture' => $request->data1['lecture'],
            'tutorial' => $request->data1['tutorial'],
            'practical' => $request->data1['practical'],
            'credit_hours' => $request->data1['credit_hours'],
            'contact_hours' => $request->data1['contact_hours'],
            'sub_type' => $request->data1['subject_type'],
            'wef_year' => $wef_year,
            'wef_session' => $wef_session,
            'pre_requisite' => $request->data1['prerequisite'],
            'pre_requisite_subcode' => $request->data1['prerequisite_sub_code'],
            'fullmarks' => $request->data1['full_marks'],
            'no_of_subjects' => $request->data1['no_of_part'],
            'sub_category' => $sub_category,
            'sub_group' => $sub_group,
            'criteria' => $request->data1['criteria'],
            'minstu' => $request->data1['min_stu'],
            'maxstu' => $request->data1['max_stu'],
            'remarks' => $request->data1['remarks'],
            'created_by' => Auth::user()->id,//need to be updated
            'action' => $ac
        ]);
        $c_id=DB::table('cbcs_subject_offered')
        ->where('session_year', $request->data1['session_year'])
        ->where('session', $request->data1['session'])
        ->where('dept_id',$dept_id)
        ->where('course_id' , $course_id)
        ->where('branch_id' , $branch_id)
        ->where('semester' , $request->data1['semester'])
        ->where('unique_sub_pool_id' , $unique_sub_pool_id)
        ->where('unique_sub_id' , 'NA')
        ->where('sub_name' , $request->data1['subject_name'])
        ->where('sub_code' , $request->data1['subject_code'])
        ->where('lecture' , $request->data1['lecture'])
        ->where('tutorial' , $request->data1['tutorial'])
        ->where('practical' , $request->data1['practical'])
        ->where('credit_hours' , $request->data1['credit_hours'])
        ->where('contact_hours' , $request->data1['contact_hours'])
        ->where('sub_type' , $request->data1['subject_type'])
        ->where('wef_year' , $wef_year)
        ->where('wef_session', $wef_session)
        ->where('pre_requisite' , $request->data1['prerequisite'])
        ->where('pre_requisite_subcode' , $request->data1['prerequisite_sub_code'])
        ->where('fullmarks' , $request->data1['full_marks'])
        ->where('no_of_subjects' , $request->data1['no_of_part'])
        ->where('sub_category' , $sub_category)
        ->where('sub_group' , $sub_group)
        ->where('criteria' , $request->data1['criteria'])
        ->where('minstu' , $request->data1['min_stu'])
        ->where('maxstu' , $request->data1['max_stu'])
        ->where('remarks' , $request->data1['remarks'])
        ->where('created_by' , Auth::user()->id)
        ->where('action' , $ac)
        ->value('id');
        foreach($request->data2 as $item){
            DB::table('cbcs_subject_offered_desc')->insert([
                'sub_offered_id' => $c_id,
                'part' => $item['part'],
                'emp_no' => $item['faculty'],
                'coordinator' => $item['marks_upload_right'],
                'sub_id' => $request->data1['subject_code'],
                'section' => $item['section'],
            ]);
        }
        return $this->sendResponse('Success', 'Success..');
    }
    public function getbatch(Request $request){
        $batch=[];
        $sep_ses_year = explode('-', $request['session_year']);
        $x=$sep_ses_year[0];
        $y=$sep_ses_year[1];
        if($request['semester']%2===1 && $request['session']==='Monsoon'){$x=$x-($request['semester']-1)/2; $y=$y-($request['semester']-1)/2;}
        if($request['semester']%2===1 && $request['session']==='Winter'){$x=$x-($request['semester']-1)/2; $y=$y-($request['semester']-1)/2;}
        if($request['semester']%2===0 && $request['session']==='Monsoon'){$x=$x-($request['semester'])/2; $y=$y-($request['semester'])/2;}
        if($request['semester']%2===0 && $request['session']==='Winter'){$x=$x-($request['semester']-2)/2; $y=$y-($request['semester']-2)/2;}
        $bat = $x .  '-' . $y;
        $batch[] = ['batch' => $bat];
        return $this->sendResponse($batch, 'Success..');
    }
    public function subdelete(Request $request){
        $courseId = DB::table('cbcs_courses')->where('name',$request->formData['course'])->where('status',1)->value('id');

        // Step 2
        $branchId = DB::table('cbcs_branches')->where('name', $request->formData['branch'])->where('status',1)->value('id');

        $departmentId = DB::table('cbcs_departments')->where('name', $request->formData['department'])->where('status',1)->value('id');
        
        if ($request->formData['branch'] === 'Common Branch for 1st Year') {
            //if($request->formData['section']===null){return $this->sendError('Failed','All fields are mandatory!');}
            if ($request->formData['section']=== 'ABCD') {
                $policyId = '1';
            } elseif ($request->formData['section']=== 'EFGH') {
                $policyId = '2';
            } else {
                $policyId = '3';
            }
        } else {
            // Policy ID logic if branch is not Common Branch for 1st Year
            $policyId = DB::table('cbcs_credit_points_policy')
                //->select(DB::raw('id'))
                ->where('wef','<=' ,$request->formData['batch'])
                ->where('course_id', $courseId)
                ->orderBy('wef', 'desc')
                ->limit(1)
                ->value('id');
        }

        if($request->formData['branch'] === 'Common Branch for 1st Year'){
            $b = DB::table('cbcs_subject_offered')
            ->select('id')
            //->select(DB::raw('course_component'))
            //->addSelect(DB::raw('sequence'))
            ->where('session_year', $request->formData['session_year'])
            ->where('session', $request->formData['session'])
            ->where('course_id', $courseId)
            ->where('branch_id', $branchId)
            ->where('dept_id', $departmentId)
            ->where('semester', $request->formData['semester'])
            ->where('sub_group', $policyId)
            ->where('unique_sub_pool_id','NA')
            ->where('sub_category', $request['course_category'])
            ->pluck('id');
        }else if(strpos($request['course_category'], '/') !== false){
            $b = DB::table('cbcs_subject_offered')
            ->select('id')
            ->where('session_year', $request->formData['session_year'])
            ->where('session', $request->formData['session'])
            ->where('course_id', $courseId)
            ->where('branch_id', $branchId)
            ->where('dept_id', $departmentId)
            ->where('semester', $request->formData['semester'])
            ->where('unique_sub_pool_id', $request['course_category'])
            ->pluck('id');
        }else{
            $b = DB::table('cbcs_subject_offered')
            ->select('id')
            ->where('session_year', $request->formData['session_year'])
            ->where('session', $request->formData['session'])
            ->where('course_id', $courseId)
            ->where('branch_id', $branchId)
            ->where('dept_id', $departmentId)
            ->where('semester', $request->formData['semester'])
            ->where('unique_sub_pool_id', 'NA')
            ->where('sub_category', $request['course_category'])
            ->pluck('id');
        }
        DB::table('cbcs_subject_offered')->whereIn('id', $b)->delete();
        DB::table('cbcs_subject_offered_desc')->whereIn('sub_offered_id', $b)->delete();
        DB::table('cbcs_subject_offered_same_branch_status')->whereIn('sub_offered_id',$b)->delete();
        return $this->sendResponse("success", 'Success..');
    }
    public function sub1delete(Request $request){
        DB::table('cbcs_subject_offered')->where('id', $request['id'])->delete();
        DB::table('cbcs_subject_offered_desc')->where('sub_offered_id', $request['id'])->delete();
        DB::table('cbcs_subject_offered_same_branch_status')->where('sub_offered_id',$request['id'])->delete();
        return $this->sendResponse("success", 'Success..');
    }
    public function fetchcoursedata(Request $request){
        $courseId = DB::table('cbcs_courses')->where('name',$request->formData['course'])->where('status',1)->value('id');

        // Step 2
        $branchId = DB::table('cbcs_branches')->where('name', $request->formData['branch'])->where('status',1)->value('id');

        $departmentId = DB::table('cbcs_departments')->where('name', $request->formData['department'])->where('status',1)->value('id');
        
        if ($request->formData['branch'] === 'Common Branch for 1st Year') {
            //if($request->formData['section']===null){return $this->sendError('Failed','All fields are mandatory!');}
            if ($request->formData['section']=== 'ABCD') {
                $policyId = '1';
            } elseif ($request->formData['section']=== 'EFGH') {
                $policyId = '2';
            } else {
                $policyId = '3';
            }
        } else {
            // Policy ID logic if branch is not Common Branch for 1st Year
            $policyId = DB::table('cbcs_credit_points_policy')
                //->select(DB::raw('id'))
                ->where('wef','<=' ,$request->formData['batch'])
                ->where('course_id', $courseId)
                ->orderBy('wef', 'desc')
                ->limit(1)
                ->value('id');
        }

        if($request->formData['branch'] === 'Common Branch for 1st Year'){
            $b = DB::table('cbcs_subject_offered')
            //->select('id')
            //->select(DB::raw('course_component'))
            //->addSelect(DB::raw('sequence'))
            ->where('session_year', $request->formData['session_year'])
            ->where('session', $request->formData['session'])
            ->where('course_id', $courseId)
            ->where('branch_id', $branchId)
            ->where('dept_id', $departmentId)
            ->where('semester', $request->formData['semester'])
            ->where('sub_group', $policyId)
            ->where('unique_sub_pool_id','NA')
            ->where('sub_category', $request['course_category'])
            ->get();
        }else if(strpos($request['course_category'], '/') !== false){
            $b = DB::table('cbcs_subject_offered')
            //->select('id')
            ->where('session_year', $request->formData['session_year'])
            ->where('session', $request->formData['session'])
            ->where('course_id', $courseId)
            ->where('branch_id', $branchId)
            ->where('dept_id', $departmentId)
            ->where('semester', $request->formData['semester'])
            ->where('unique_sub_pool_id', $request['course_category'])
            ->get();
        }else{
            $b = DB::table('cbcs_subject_offered')
            //->select('id')
            ->where('session_year', $request->formData['session_year'])
            ->where('session', $request->formData['session'])
            ->where('course_id', $courseId)
            ->where('branch_id', $branchId)
            ->where('dept_id', $departmentId)
            ->where('semester', $request->formData['semester'])
            ->where('unique_sub_pool_id', 'NA')
            ->where('sub_category', $request['course_category'])
            ->get();
        }
        return $this->sendResponse($b, 'Success..');
    }
}
