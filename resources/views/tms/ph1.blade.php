<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
    {{-- <link href="{{ public_path('css\bootstrap.min.css') }}" rel="stylesheet" media="all" type="text/css">
    <script src="{{ public_path('js\jquery.slim.min.js') }}"></script>
    <script src="{{ public_path('js\popper.min.js') }}"></script>
    <script src="{{ public_path('js\bootstrap.bundle.min.js') }}"></script>
    <script src="{{ public_path('js\bootstrap.min.js') }}"></script> --}}
  
</head>
<style>
@media print{
    .onepage {
        page-break-inside: avoid !important; 
        page-break-after: always !important;
    }
}
.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px
}

/* .container,
.container-fluid,
.container-lg,
.container-md,
.container-sm,
.container-xl {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto
} */

body{
    font-size: 16px;
}

</style>

<body>



    <div class="container-fluid">
        <div class="row" style="padding: 0%;display: flex">
            <div class="col-sm-1">
                <img style=""
                    src="https://mis.iitism.ac.in/assets/images/employee_daily_rated/acad_da/emp_acad_da_20201023190512.jpg"
                    width="80px" />
            </div>
            <div class="col-sm-12" style="text-align: center;margin-top: -55px;margin-right: 30px">
                <span style="text-decoration: underline;float: right;margin-top: -10px"> Form No: PH1 </span>
                <p style="font-family: emoji;font-weight:bold;margin-left: 50px">INDIAN INSTITUTE OF
                    TECHNOLOGY (INDIAN SCHOOL
                    OF MINES) DHANBAD  </p>
                <h6 style="color: red;">FORM FOR GIVING WAIVER TO PHD SCHOLAR IN COURSE WORK</h6>
                <h6>(Only for Ph.D. Scholar who Completed Master Degree from any of the IITs)</h6>

            </div>
            {{-- <div class="col-sm-1">
                <h6 style="text-decoration: underline;font-weight:bold">Form No: PH1 </h6>
            </div> --}}
        </div>
        <hr>
        <div class="row" style="padding-left: 3%;padding-right: 3%;">
            <table width="100%" class="table table-bordered" style="border-color: black">
                <tbody>
                    <tr>                      
                            <td><b>Academic Session: </b></td>
                            <td>{{$ph1_details->session_year ?? ''}}</td>
                            <td><b>Semester</b></td>
                            <td>{{$ph1_details->session ?? ''}}</td>                         
                    </tr>                    
                </tbody>
            </table>

            <table width="100%" class="table table-bordered" style="border-color: black">
                <tbody>
                    <tr>                      
                            <td style="width: 1%">1</td>
                            <td style="width: 20%">Name of Scholar</td>
                            <td style="width: 69%">{{$stu_data->user_name}}</td>
                                              
                    </tr>
                </tbody>  
            </table>
            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1 !important%">
                <tbody>
                    <tr>                      
                        <td style="width: 1%">2</td>
                   
                        <td style="width: 20%">Admission No</td>
                  
                        <td style="width: 30%">{{$ph1_details->admn_no ?? ''}}</td>
                        <td style="width: 29%">Date of Ph.D Admission</td>
                        <td style="width: 20%">{{$stu_data->admn_date ?? ''}}</td>
                                          
                </tr>                    
                </tbody>
            </table>
            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>                      
                        <td style="width: 1%">3</td>                   
                        <td style="width: 40%">Registration Status of the Scholar (Put √ Mark)</td>                  
                        <td ><div class="form-check">
                            <input class="form-check-input" type="radio" id="checkbox" name="option1" value="something" {{($stu_data->other_rank=='fulltime') ? 'checked' : ''}} >
                            <label class="form-check-label">Full Time</label>
                          </div></td>
                       
                        <td ><div class="form-check">
                            <input class="form-check-input" type="radio" id="checkbox" name="option1" value="something" {{($stu_data->other_rank=='parttime') ? 'checked' : ''}}>
                            <label class="form-check-label">Part Time</label>
                          </div></td>
                       
                        <td ><input class="form-check-input" type="radio" id="checkbox" name="option1" value="something" {{($stu_data->other_rank=='external') ? 'checked' : ''}}>
                            <label class="form-check-label">External</label></td>
                                                               
                </tr>                    
                </tbody>
            </table>
            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>                      
                        <td style="width: 1%">4</td>                   
                        <td style="width: 20%">Department</td>                  
                       
                        <td style="width: 35%">{{$stu_data->dept_id ?? ''}}</td>
                        <td style="width: 20%">Branch (if any)</td>
                        <td >{{$stu_data->branch_id ?? ''}}</td>
                                                            
                </tr>                    
                </tbody>
            </table>
            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>                      
                        <td style="width: 1%">5</td>                   
                        <td style="width: 30%">Institute Email ID</td>   
                        <td style="width: 69%">{{$stu_data->domain_name ?? ''}}</td>                
                                                            
                </tr>                    
                </tbody>
            </table>
            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>                      
                        <td style="width: 1%">6</td>                   
                        <td style="width: 30%">Contact Number of Scholar</td>   
                        <td style="width: 69%">{{$stu_data->mobile_no ?? ''}}</td>                
                                                            
                </tr>                    
                </tbody>
            </table>

            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>                      
                        <td style="width: 1%">7</td>                   
                        <td style="width: 30%">Qualifying Degree during Ph.D Admission</td>   
                        <td style="width: 20%">{{$ph1_details->qualifying_degree ?? ''}}</td>           
                        <td style="width: 30%">PG Degree was in relevant field?(Put √ Mark)</td> 
                        <td><div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check1" name="option1" value="something" {{($ph1_details->verified_qualifying_degree=='Yes') ? 'checked' : ''}}>
                            <label class="form-check-label">Yes</label>
                          </div>
                        </td> 
                        <td><div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check1" name="option1" value="something" {{($ph1_details->verified_qualifying_degree=='No') ? 'checked' : ''}}>
                            <label class="form-check-label">No</label>
                          </div>
                        </td> 
                        <td><div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check1" name="option1" value="something" {{($ph1_details->verified_qualifying_degree=='NA') ? 'checked' : ''}}>
                            <label class="form-check-label">Not Applicable</label>
                          </div>
                        </td>                         
                </tr>                    
                </tbody>
            </table>
           

        </div>

        <div class="row" style="padding-left: 0%;padding-right: 0%;">
        <div class="col-md-12" style="display: inline-flex">
            <table>
                <tr>
                    <td style="width: 40%">  
                    <div class="col-md-4" style="width: 100%">
                        <table width="100%" class="table table-bordered" style="margin-top:0% !important;padding-top:0% !important">
                            <thead>
                                <tr style="text-align: center">                      
                                  <td colspan="5">PRESCRIBED COURSES AS PER COURSE
                                    STRUCTURE</td>                     
                                </tr> 
                                <tr>                      
                                    <td style="width: 1%">Sl. No</td> 
                                    <td style="width: 10%">Course Code</td>
                                    <td style="width: 20%">Title of the Course & Credits</td>                    
                                  </tr>                   
                            </thead>
                            <tbody>                              
                               
                                    <?php
                                    $i=0;
                                    foreach($ph1_prescribed_course as $key=>$value){
                                        $i++;
                                        ?>

                                        <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$value->course_code}}</td>
                                        <td>{{$value->course_name}} ({{$value->course_credit}})</td>
                                       
                                    </tr>
                                <?php    
                                }
                                    ?>


                            </tbody>
                        </table>
               
                    </div> 
                </td>
                    <td style="width: 20%">     
                    <div class="col-md-2" style="width: 100%">
                        <table width="100%" class="table table-bordered" style="margin-top:0% !important;padding-top:0% !important">
                            <thead>
                                <tr style="text-align: center">                      
                                  <td colspan="1">PROPOSED COURSES TO BE WAIVED</td>                     
                                </tr> 
                                <tr>                      
                                   
                                    <td style="width: 10%">Course Code</td>
                                               
                                  </tr>                   
                            </thead>
                            <tbody>                              
                               
                                <?php
                                $i=0;
                                foreach($ph1_waived_course as $key=>$value){
                                    $i++;
                                    ?>

                                    <tr>
                                   
                                    <td>{{$value->course_code}}</td>
                               
                                   
                                </tr>
                            <?php    
                            }
                                ?>


                        </tbody>
                        </table>
                    </div> 
                </td>

                <td style="width: 40%">  
                    <div class="col-md-4" style="width: 100%">
                        <table width="100%" class="table table-bordered" style="margin-top:0% !important;padding-top:0% !important">
                            <thead>
                                <tr style="text-align: center">                      
                                  <td colspan="5">DETAILS OF THE COURSE
                                    CLEARED DURING MASTER
                                    DEGREE IN LIEU OF REQUESTED
                                    WAIVER</td>                     
                                </tr> 
                                <tr>                      
                                    <td style="width: 1%">Sl. No</td> 
                                    <td style="width: 10%">Course Name</td>
                                    <td style="width: 20%">Grade Obtained</td>                    
                                  </tr>                   
                            </thead>
                            <tbody>                              
                               
                                <?php
                                $i=0;
                                foreach($ph1_prescribed_course as $key=>$value){
                                    $i++;
                                    ?>

                                    <tr>
                                    <td>{{$i}}</td>                                  
                                    <td>{{$value->course_name}}</td>
                                    <td>{{$value->course_grade}}</td>
                                </tr>
                            <?php    
                            }
                                ?>


                        </tbody>
                    </table>
                        </table>
               
                    </div> 
                </td>


                </tr>

            </table>
       
                <p>I hereby declare that my request for waiver in course work of Ph.D. Program is made as per the Senate approved norms
                    given in the Ph.D. Manual [“A Ph.D. student who has completed Master degree* from any IIT can be waived off a maximum
                    of three courses (Two Compulsory Departmental Papers and One Compulsory Paper on Numerical Methods/Modelling
                    Simulation, uses of Python/MATLAB/Mathematica etc.). However, the students registered for Department of Humanities and
                    Social Science can have a waiver of maximum of two papers i.e., Two Compulsory Departmental Papers.”] and I will be
                    responsible for any kind of discrepancy in completion of my academic program due to the waiver in the above requested
                    course work.
                    </p>
                    <p>* From same department </p>
                    <table style="margin-top: 10%">
                        <tr>
                            <td style="width: 100%">  Date :  {{$ph1_details->stu_submit_date}}</td>
                            <td style="width: 100%;float: right;margin-right:70px"> 
                                <img width="100" src="https://mis.iitism.ac.in/assets/images/{{$stu_data->signpath}}"/>
                                <p>(Signature of Scholar)</p> </td>
                        </tr>
                    </table>
                    <table style="margin-top: 10%">
                        <tr>
                            <td style="width: 100%"> <b>Reasons for giving course waiver:</b></td>
                            <td style=""> {{$ph1_details->reasons_for_giving_course_waiver ?? ''}} </td>
                        </tr>
                    </table><br>
                   
        <div style="margin-top: 25%;text-align: center">
                    <p >Recommended by DPGC as it is consistent with the Senate Guidelines (Signature with Date)</p>
        </div>       
                    <p style="text-align: center"><b>For Office Use only</b></p>
                    <table class="table table-bordered">
                        <tr >
                            <td style="width: 50%">Verified the Qualifying Degree
                                of the scholar during Ph.D
                                Admission</td>
                            <td style="width: 25%">
                            <input type="checkbox" class="form-control" value="Yes" {{($ph1_details->verified_qualifying_degree=='Yes') ? 'checked' : ''}} /> Yes
                            </td>
                            <td style="width: 25%"> <input type="checkbox" class="form-control" value="Yes" {{($ph1_details->verified_qualifying_degree=='No') ? 'checked' : ''}} /> No</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">Verified the Academic record of
                                the Scholar</td>
                            <td style="width: 25%"> <input type="checkbox" class="form-control" value="No" {{($ph1_details->verified_academic_record=='Yes') ? 'checked' : ''}} /> Yes</td>
                            <td style="width: 50%"> <input type="checkbox" class="form-control" value="NA" {{($ph1_details->verified_academic_record=='No') ? 'checked' : ''}} /> No</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">Observations, if any</td>
                            <td style="width: 50%" colspan="2">{{$ph1_details->any_observations ?? ''}}</td>
                           
                        </tr>
                    </table>
                    <table class="" style="margin-top: 15%">
                        <tr>
                            <td style="width: 100%">Dealing Assistant</td>
                            <td style="width: 100%;float: right;margin-right: 200px">AR (Academic - (PG) / DR (Academic) </td>
                        </tr>
                    </table>
                    <p style="text-align: center;margin-top: 5%">
                                             
                            <div class="form-check" style="text-align: center">
                                <input class="form-check-input" type="checkbox" id="check1" name="option1" value="something" {{($ph1_details->accepted_for_next_meeting=='1') ? 'checked' : ''}}>
                                <label class="form-check-label"> Accepted for the next Standing Committee Meeting</label>
                              </div>                           
                       </p>

                    <p style="text-align: center;margin-top: 10%"><b>Associate Dean (Academic – PG) / Dean (Academic)</b></p>
                    <p style="margin-top: 10%"><b>Date</b></p>
    
        </div>

    </div>
    </div>

</body>

</html>