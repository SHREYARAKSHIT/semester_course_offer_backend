<!DOCTYPE html>
<html lang="en">

<head>
    <title>PH4 Form - <?php echo $stu_data->id; ?> </title>
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
                <h6 style="margin-top: 15px">(This report of the Comprehensive Examination must be sent to the Associate
                    Dean (Academic - PG)
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
                        <td style="width: 30%">Date of Research Proposal Seminar</td>
                        <td style="width: 69%">
                            {{ date_format(date_create($ph5_data[0]->date_of_research_proposal_seminar), 'd-m-Y') ?? '' }}
                        </td>

                    </tr>
                </tbody>
            </table>



            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">6</td>

                        <td style="width: 30%">Title of Proposed Research</td>
                        <td>
                            {{ $ph5_data[0]->title_of_proposed_research ?? '' }}
                        </td>


                    </tr>

                </tbody>
            </table>
            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">6</td>

                        <td style="width: 30%">Research Proposal File</td>
                        <td>

                            @if ($ph5_data[0]->research_proposal_file)
                                <a href="<?php echo env('APP_URL', 'http://localhost') . '/' . $ph5_data[0]->research_proposal_file; ?>" target="_blank">Download</a>
                            @else
                                NA
                            @endif


                        </td>


                    </tr>

                </tbody>
            </table>

            <table width="100%" class="table table-bordered" style="padding-top:-5% !important;border: none;">
                <tbody>
                    <tr style="border: none;">
                        <td style="border: none;">
                            <p>(The Research Proposal of 4-5 pages is to be appended as Annexure-I that may typically
                                include 1. Title, 2. Introduction, 3. State-of-the-art, 4.
                                Research / Knowledge Gap, 5. Present Research Objective, 6. Research Proposal with
                                Tentative Experimental / Theoretical Framework, 7.
                                Conclusion and 8. References [in Harvard Style])
                            </p>
                        </td>



                </tbody>
            </table>





            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">7</td>
                        <td style="width: 20%">Brief Comments on the
                            Student’s Performance in
                            Research Proposal
                            Seminar:</td>
                        <td style="width: 30%">
                            {{ $ph5_data[0]->brief_student_performance ?? '' }}
                        </td>

                    </tr>

                </tbody>
            </table>

            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">8</td>
                        <td style="width: 40%">Overall Recommendation:</td>
                        <td style="width: 30%">
                            {{ $ph5_data[0]->overall_recommendation ?? '' }}
                        </td>

                    </tr>

                </tbody>
            </table>

            <div class="col-sm-12">
                <h5 style="text-decoration:underline">Signature of DSC Members present in the Comprehensive
                    Examination:</h5>
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
                        @if (is_countable($extGuide))
                            @foreach ($extGuide as $key => $values)
                                @php
                                    $i++;
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $values->ext_name ?? '' }}</td>
                                    <td>{{ $values->ext_ins ?? '' }}</td>
                                    <td>{{ $values->ext_email ?? '' }}</td>
                                    <td>{{ $values->ext_phone ?? '' }}</td>
                                    <td></td>

                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>

            <div class="col-sm-12">
                <?php
              if(count($form_status) > 0 ){
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
