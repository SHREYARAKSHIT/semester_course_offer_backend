<!DOCTYPE html>
<html lang="en">

<head>
    <title>PH4 Form - <?php echo $admn_no; ?> </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



</head>
<style>
    .pagenum:before {
        content: counter(page);
    }

    @media print {
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

    body {
        font-size: 13.5px;
    }

    #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

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
                <img style="" src="#" width="80px" />
            </div>
            <div class="col-sm-12" style="text-align: center;margin-top: -55px;">

                <p style="font-family: emoji;font-weight:bold;margin-left: 45px">INDIAN INSTITUTE OF
                    TECHNOLOGY (INDIAN SCHOOL
                    OF MINES) DHANBAD </p>
                <h6 style="color: red;margin-left: 45px">COMPREHENSIVE EXAMINATION REPORT</h6>
                <h6 style="margin-top: 15px">(This report of the Comprehensive Examination must be sent to the Associate Dean (Academic - PG)
                    within ONE week)</h6>

            </div>
            <div class="col-sm-12">
                <h6 style="text-decoration: underline;font-weight:bold;float: right;margin-right:30px">Form No: PH4
                </h6>
            </div>
        </div>
        <hr>
        <div class="row" style="padding-left: 3%;padding-right: 3%;">

            <table width="100%" class="table table-bordered" style="border-color: black">
                <tbody>
                    <tr>
                        <td style="width: 1%">1</td>
                        <td style="width: 20%">Name of Scholar</td>
                        <td style="width: 69%">{{ $stu_data->user_name ?? '' }}</td>

                    </tr>
                </tbody>
            </table>
            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1 !important%">
                <tbody>
                    <tr>
                        <td style="width: 1%">2</td>

                        <td style="width: 20%">Admission No</td>

                        <td style="width: 30%">{{ $stu_data->id ?? '' }}</td>
                        <td style="width: 29%">Date of Ph.D Admission (YYYY-MM-DD)</td>
                        <td style="width: 20%">{{ $stu_data->admn_date ?? '' }}</td>

                    </tr>
                </tbody>
            </table>
            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">3</td>
                        <td style="width: 40%">Registration Status of the Scholar (Put √ Mark)</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox" <?php if ($stu_data->other_rank == 'fulltime') {
                                    echo 'checked';
                                } ?>
                                    name="option1" value="something">
                                <label class="form-check-label">Full Time</label>
                            </div>
                        </td>

                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" <?php if ($stu_data->other_rank == 'parttime') {
                                    echo 'checked';
                                } ?> id="checkbox"
                                    name="option1" value="something">
                                <label class="form-check-label">Part Time</label>
                            </div>
                        </td>

                        <td><input class="form-check-input" type="checkbox" id="checkbox" <?php if ($stu_data->other_rank == 'external') {
                            echo 'checked';
                        } ?>
                                name="option1" value="something">
                            <label class="form-check-label">External</label>
                        </td>

                    </tr>
                </tbody>
            </table>
            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">4</td>
                        <td style="width: 20%">Department</td>

                        <td style="width: 35%">{{ $stu_data->dept_name ?? '' }}</td>
                        <td style="width: 20%">Branch (if any)</td>
                        <td>{{ $stu_data->branch_name ?? '' }}</td>

                    </tr>
                </tbody>
            </table>
            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">5</td>
                        <td style="width: 30%">Institute Email ID</td>
                        <td style="width: 69%">{{ $stu_data->domain_name ?? '' }}</td>

                    </tr>
                </tbody>
            </table>
            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">6</td>
                        <td style="width: 30%">Date of Comprehensive Examination</td>
                        <td style="width: 69%">
                            {{ date_format(date_create($ph4_data[0]->date_of_comprehensive_examination), 'd-m-Y') ?? '' }}
                        </td>

                    </tr>
                </tbody>
            </table>

            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">7</td>
                        <td style="width: 30%">Qualifying Degree during Ph.D Admission</td>
                        <td style="width: 20%">{{ $ph4_data[0]->qualifying_degree ?? '' }}</td>
                        <td style="width: 30%">Was PG Degree in the relevant field?
                            (Put √ Mark)</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check1"
                                    checked=<?php echo $ph4_data[0]->pg_in_relevant_field ? true : false; ?> name="option1" value="something">
                                <label class="form-check-label">Yes</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check1" name="option1"
                                    value="something">
                                <label class="form-check-label">No</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check1" name="option1"
                                    value="something">
                                <label class="form-check-label">Not Applicable</label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">8</td>

                        <td style="width: 30%">Mode of Comprehensive Examination</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check1"
                                    checked=<?php echo $ph4_data[0]->mode_of_comprehensive_examination == 'written' ? 'checked' : ''; ?> name="written" value="written">
                                <label class="form-check-label">*Written</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check1"
                                    checked=<?php echo $ph4_data[0]->mode_of_comprehensive_examination == 'oral' ? 'checked' : ''; ?> name="oral" value="oral">
                                <label class="form-check-label">Oral</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check1"
                                    checked=<?php echo $ph4_data[0]->mode_of_comprehensive_examination == 'both' ? 'checked' : ''; ?> name="both" value="both">
                                <label class="form-check-label">Both </label>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>

            <table width="100%" class="table table-bordered" style="padding-top:-5% !important;border: none;">
                <tbody>
                    <tr style="border: none;">
                        <td style="border: none;">
                            <p>*Written part of the Comprehensive Examination is optional and the decision is left to
                                the DSCs. However, the Oral examination will be mandatory. If any
                                department decides to conduct the written examination, they should conduct the
                                examination for all the students of the department. The decision of the department
                                to conduct a written examination should be communicated to the Dean (Academic) in
                                advance.
                            </p>
                        </td>



                </tbody>
            </table>

           
            <h5 style="text-decoration:underline;margin-bottom: 10px">8. List of Courses suggested by the DSC and cleared by scholar:</h5>
            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Course Type
                            (DC/DE/OE)</th>
                        <th>Course Credit</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($ph3Courses as $key => $value)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$value->sub_code}}</td>
                            <td>{{$value->sub_name}}</td>
                            <td>{{$value->sub_category}}</td>
                            <td>{{$value->cr_hr}}</td>
                            <td>{{$value->grade}}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            
            <h5 style="text-decoration:underline">9. Eligibility Criteria:</h5>
            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 30%">Completed the assigned courses (Put √ Mark)</td>
                        <td style="width: 30%">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check1"
                                    checked=<?php echo $ph4_data[0]->pg_in_relevant_field ? true : false; ?> name="option1" value="something">
                                <label class="form-check-label">Yes</label>
                            </div>
                        </td>
                        <td style="width: 30%" colspan="2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check1" name="option1"
                                    value="something">
                                <label class="form-check-label">No</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Current CGPA</td>
                        <td style="width: 15%">{{ $ph4_data[0]->current_cgpa ?? '' }}</td>

                        <td style="width: 30%">Total Course Credit Earned</td>
                        <td style="width: 15%">{{ $ph4_data[0]->total_course_credit_earned ?? '' }}</td>

                    </tr>
                </tbody>
            </table>
            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">10</td>
                        <td style="width: 40%">Result of Comprehensive Examination:</td>
                        <td style="width: 30%">
                            {{ $ph4_data[0]->result_of_comprehensive_examination ?? '' }}
                        </td>

                    </tr>

                </tbody>
            </table>

            <div class="col-sm-12">
                <h5 style="text-decoration:underline">Signature of DSC Members present in the Comprehensive Examination:</h5>
                <table width="100%" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Department</th>
                            <th>Faculty</th>
                            <th>Position</th>
                            <th>Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($dscdetails as $key => $value)
                            @php
                                $i++;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $value->dept_name }}</td>
                                <td>{{ $value->emp_name }}</td>
                                <td>{{ $value->role }}</td>
                                <td></td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="col-sm-12">
                <h5 style="text-decoration:underline">Supervisor & Co-Supervisor (if any)</h5>
                <table width="100%" class="table table-bordered">
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
                            $i = 0;
                        @endphp
                        @foreach ($guide as $key => $value)
                            @php
                                $i++;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $value->dept_name }}</td>
                                <td>{{ $value->guide_name }}</td>
                                <td>{{ $value->role }}</td>
                                <td></td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12">
                <h5 style="text-decoration:underline">External Co-Supervisor, if any</h5>
                <table width="100%" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Name</th>
                            <th>Institute</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Signature</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($extGuide as $key => $value)
                            @php
                                $i++;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $value->ext_name }}</td>
                                <td>{{ $value->ext_ins }}</td>
                                <td>{{ $value->ext_email }}</td>
                                <td>{{ $value->ext_phone }}</td>
                                <td></td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12">
                <?php
              if(count($form_status) > 0){
              if($form_status[0]->level==='0'){ ?>

                <h4 style="color:red">Status : Form submitted by <?php echo $form_status[0]->ft_name . ' (' . $form_status[0]->auth_name . ')'; ?> at <?php echo $form_status[0]->updated_at; ?></h4>

                <?php    }else{ ?>

                <h5 style="color:red">Status : Form <?php echo $form_status[0]->application_status == 'approvedandforword' ? 'Approved and forwarded' : $form_status[0]->application_status; ?> by <?php echo $form_status[0]->ft_name . ' (' . $form_status[0]->auth_name . ')'; ?> at
                    <?php echo $form_status[0]->updated_at; ?></h5>
                <?php  }
              }
              ?>

            </div>
            <div class="col-sm-12">
                <p style="text-decoration:'underline'">System Generated Receipt***</p>

            </div>

        </div>


    </div>

</body>

</html>
