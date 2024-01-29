<!DOCTYPE html>
<html lang="en">

<head>
    <title>PH2 Form </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



</head>
<style>
    .pagenum:before {
        content: counter(page);
    }
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
@page {
        size: A4 portrait;
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
    font-size: 13.5px;
}

#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

</style>

<body>
    <div class="container-fluid">
        <div class="row" style="padding: 0%;display: flex">
            <div class="col-sm-1">
                <img style=""
                    src="{{ URL::asset('assets/ism.jpg')}}"
                    width="80px" />
            </div>
            <div class="col-sm-12" style="text-align: center;margin-top: -55px;">

                <p style="font-family: emoji;font-weight:bold;margin-left: 45px">INDIAN INSTITUTE OF
                    TECHNOLOGY (INDIAN SCHOOL
                    OF MINES) DHANBAD </p>
                <h6 style="color: red;margin-left: 45px">FORM FOR CONSTITUTION OF DOCTORAL SCRUTINY COMMITTEE (DSC)</h6>
                <!-- <h6>(To be filled up at the end of First Semester)</h6> -->

            </div>
            <div class="col-sm-12" >
                <h6 style="text-decoration: underline;font-weight:bold;float: right;margin-right:30px">Form No: PH2 </h6>
            </div>
        </div>
        <hr>
        <div class="row" style="padding-left: 3%;padding-right: 3%;">
            <table width="100%" class="table table-bordered" style="border-color: black">
                <tbody>
                    <tr>
                            <td><b>Academic Session: </b></td>
                            <td>{{$ph2_data[0]->session_year ?? ''}}</td>
                            <td><b>Semester</b></td>
                            <td>{{$ph2_data[0]->session ?? ''}}</td>
                    </tr>
                </tbody>
            </table>

            <table width="100%" class="table table-bordered" style="border-color: black">
                <tbody>
                    <tr>
                            <td style="width: 1%">1</td>
                            <td style="width: 20%">Name of Scholar</td>
                            <td style="width: 69%">{{$stu_data->user_name ?? ''}}</td>

                    </tr>
                </tbody>
            </table>
            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1 !important%">
                <tbody>
                    <tr>
                        <td style="width: 1%">2</td>

                        <td style="width: 20%">Admission No</td>

                        <td style="width: 30%">{{$stu_data->id ?? ''}}</td>
                        <td style="width: 29%">Date of Ph.D Admission (YYYY-MM-DD)</td>
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
                            <input class="form-check-input" type="checkbox" id="checkbox"
                            <?php if($stu_data->other_rank=='fulltime') {  echo "checked"; } ?>
                            name="option1" value="something"  >
                            <label class="form-check-label">Full Time</label>
                          </div></td>

                        <td ><div class="form-check">
                            <input class="form-check-input" type="checkbox"   <?php if($stu_data->other_rank=='parttime') {  echo "checked"; } ?>  id="checkbox" name="option1" value="something" >
                            <label class="form-check-label">Part Time</label>
                          </div></td>

                        <td ><input class="form-check-input" type="checkbox" id="checkbox" <?php if($stu_data->other_rank=='external') {  echo "checked"; } ?> name="option1" value="something">
                            <label class="form-check-label">External</label></td>

                </tr>
                </tbody>
            </table>
            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">4</td>
                        <td style="width: 20%">Department</td>

                        <td style="width: 35%">{{$stu_data->dept_name ?? ''}}</td>
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
                        <td style="width: 30%">Contact Number</td>
                        <td style="width: 69%">{{$stu_data->mobile_no ?? ''}}</td>

                </tr>
                </tbody>
            </table>

            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">7</td>
                        <td style="width: 30%">Qualifying Degree during Ph.D Admission</td>
                        <td style="width: 20%">{{$ph2_data[0]->qualifying_degree ?? ''}}</td>
                        <td style="width: 30%">Was PG Degree in the relevant field?
(Put √ Mark)</td>
                        <td><div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check1" checked= <?php echo ($ph2_data[0]->pg_in_relevant_field ? true : false) ?> name="option1" value="something" >
                            <label class="form-check-label">Yes</label>
                          </div>
                        </td>
                        <td><div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check1" name="option1" value="something">
                            <label class="form-check-label">No</label>
                          </div>
                        </td>
                        <td><div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check1" name="option1" value="something">
                            <label class="form-check-label">Not Applicable</label>
                          </div>
                        </td>
                </tr>
                </tbody>
            </table>
            <table width="100%" class="table table-bordered" style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">8</td>
                        <td style="width: 30%">Broad Area of Research Work</td>
                        <td style="width: 69%">{{$ph2_data[0]->area_of_research ?? ''}}</td>

                </tr>
                </tbody>
            </table>
            <div class="col-sm-12" >
            <h5 style="text-decoration:underline">9. Details of the Proposed Doctoral Scrutiny Committee (DSC) to be constituted for the Scholar:</h5>
        </div>
            <div class="col-sm-12" >
                <h4 style="text-decoration:underline"> 9.1 Chairperson</h4>
                <table width="100%" class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Department</th>
                            <th>Name</th>
                            <th>Area of Specialization</th>
                            <th>Action</th>
                            <th>Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i=0;
                        @endphp
                        @foreach ($ph2_dsc_chairman as $key=>$value)
                        @php
                        $i++;
                        @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$value->dept_name ?? ''}}</td>
                                    <td>{{$value->emp_id ?? ''}}</td>
                                    <td>{{$value->area_of_specilization ?? ''}}</td>
                                    <td>{{$value->selected ?? 'NA'}}</td>
                                    <td></td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12" >
                <h5 style="text-decoration:underline"> 9.2 Member</h5>
                <table width="100%" class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Department</th>
                            <th>Name</th>
                            <th>Area of Specialization</th>
                            <th>Action</th>
                            <th>Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i=0;
                        @endphp
                        @foreach ($ph2_dsc_member as $key=>$value)
                        @php
                        $i++;
                        @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$value->dept_name ?? ''}}</td>
                                    <td>{{$value->emp_id ?? ''}}</td>
                                    <td>{{$value->area_of_specilization ?? ''}}</td>
                                    <td>{{$value->selected ?? 'NA'}}</td>
                                    <td></td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-sm-12" >
                <h5 style="text-decoration:underline"> 9.3 Supervisor</h5>
                <table width="100%" class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Department</th>
                            <th>Name</th>
                            <th>Area of Specialization</th>
                            <th>Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i=0;
                        @endphp
                        @foreach ($guide as $key=>$value)
                        @php
                        $i++;
                        @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{($value->dept_name) ?? ''}}</td>
                                    <td>{{$value->guide_name."-".$value->guide ?? ''}}</td>
                                      <td>{{$value->research_interest_eng ?? ''}}</td>
                                    <td></td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12" >
                <h5 style="text-decoration:underline"> 9.3 *Co-Supervisor</h5>
                <table width="100%" class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Department</th>
                            <th>Name</th>
                            <th>Area of Specialization</th>
                            <th>Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i=0;
                        @endphp
                        @foreach ($coguide as $key=>$value)
                        @php
                        $i++;
                        @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{($value->dept_name) ?? ''}}</td>
                                    <td>{{$value->coguide_name."-".$value->guide ?? ''}}</td>
                                    <td>{{$value->research_interest_eng ?? ''}}</td>
                                    <td></td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12" >
                <h5 style="text-decoration:underline"> 9.4 **External Co-Supervisor, if any</h5>
                <table width="100%" class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Name</th>
                            <th>Institute</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Area of Specialization</th>

                            <th>Consent Letter</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i=0;
                        @endphp
                        @foreach ($ph2_external as $key=>$value)
                        @php
                        $i++;
                        @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{($value->name) ?? ''}}</td>
                                    <td>{{$value->institute ?? ''}}</td>
                                    <td>{{$value->email ?? ''}}</td>
                                    <td>{{$value->contact_no ?? ''}}</td>
                                    <td>{{$value->area_of_specilization ?? ''}}</td>
                                    <td><a target="_blank" href="{{"https://tmsapi.iitism.ac.in/".$value->consent_letter ?? '#'}}">View</a></td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12" >
                <p>* Justification of inclusion to be submitted along with this form for the necessary approval from the Chairman, Senate. </p>

            </div>
            <div class="col-sm-12" >
                <p>** Justification of inclusion, her/his Consent letter and Bio-Data to be submitted along with this form for the necessary approval from the Chairman, Senate. </p>

            </div>
            <div class="col-sm-12" >
              <?php
              if($form_status[0]->auth_id===null){ ?>

                  <h4 style="color:red">Status : Form submitted by <?php echo $form_status[0]->ft_name." (".$form_status[0]->auth_name.")"; ?></h4>

          <?php    }else{ ?>

                    <h4 style="color:red">Status : Form <?php echo ($form_status[0]->application_status=="approvedandforword") ? "Approved and forward" : $form_status[0]->application_status ; ?> by <?php echo $form_status[0]->ft_name." (".$form_status[0]->auth_name.")"; ?></h4>
            <?php  }

              ?>

            </div>
            <div class="col-sm-12" >
                <p style="text-decoration:'underline'">System Generated Receipt***</p>

            </div>

        </div>


    </div>

</body>

</html>
