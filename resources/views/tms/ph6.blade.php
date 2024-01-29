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
                        <td style="width: 40%">Registration Type of the Scholar (Put √ Mark)</td>
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
                        <td style="width: 40%"> Registration Status of the Scholar</td>
                        <td>
                            <table idth="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="text-align:center">CURRENT SEMESTER</th>
                                        <th style="text-align:center">NEXT SEMESTER</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Preregistration :</td>
                                        <td style="text-align:center">{{ $curr_reg }}</td>
                                        <td style="text-align:center">{{ $next_reg }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fee Payment :</td>
                                        <td style="text-align:center">{{ $curr_fee }}</td>
                                        <td style="text-align:center">{{ $next_fee }}</td>
                                    </tr>
                                    <tr>
                                        <td>Physical Reporting :</td>
                                        <td style="text-align:center">{{ $curr_phy_reporting }}</td>
                                        <td style="text-align:center">{{ $next_phy_reporting }}</td>
                                    </tr>

                                </tbody>

                            </table>


                        </td>
                    </tr>

            </table>

            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">5</td>
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
                        <td style="width: 1%">6</td>
                        <td style="width: 30%">Qualifying Degree during Ph.D Admission</td>
                        <td style="width: 20%">{{ $ph6_data[0]->qualifying_degree ?? '' }}</td>
                        <td style="width: 30%">Was PG Degree in the relevant field?
                            (Put √ Mark)</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check1"
                                    checked=<?php echo $ph6_data[0]->pg_in_relevant_field ? true : false; ?> name="option1" value="something">
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
                    </tbod <table width="100%" class="table table-bordered"
                        style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">7</td>

                        <td style="width: 30%">Title of the Thesis</td>
                        <td>
                            {{ $ph6_data[0]->title_of_proposed_research ?? '' }}
                        </td>


                    </tr>

                </tbody>
            </table>



            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">8A</td>
                        <td style="width: 30%">Details of Course Work (Please specify number of courses)</td>
                        <td style="width: 20%">Assigned : {{ $ph6_data[0]->course_work_assigned ?? '' }}</td>
                        <td style="width: 20%">Completed : {{ $ph6_data[0]->course_work_completed ?? '' }}</td>
                        <td style="width: 20%">Waivedoff, if any : {{ $ph6_data[0]->course_work_waivedoff ?? '' }}
                        </td>

                    </tr>
                </tbody>
            </table>

            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">8B</td>
                        <td style="width: 30%">Research Credit Earned (Eligibility is 24S i.e., 216 research credits
                            from 2018 batch)</td>
                        <td style="width: 20%">{{ $ph6_data[0]->research_credit_earned ?? '' }}</td>


                    </tr>
                </tbody>
            </table>

            <table width="100%" class="table table-bordered"
                style="margin-top:-1% !important;padding-top:-1% !important">
                <tbody>
                    <tr>
                        <td style="width: 1%">9</td>
                        <td style="width: 40%">The draft copy of the thesis is ready (Please Check)</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox" <?php if ($ph6_data[0]->draft_copy_thesis_ready == 'Yes') {
                                    echo 'checked';
                                } ?>
                                    name="option1" value="something">
                                <label class="form-check-label">Yes</label>
                            </div>
                        </td>

                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" <?php if ($ph6_data[0]->draft_copy_thesis_ready == 'No') {
                                    echo 'checked';
                                } ?> id="checkbox"
                                    name="option1" value="something">
                                <label class="form-check-label">No</label>
                            </div>
                        </td>



                    </tr>
                </tbody>
            </table>

            <h4 style="text-decoration:underline">10. Summary of Research Papers published/accepted by the scholar
                based on thesis work:</h4>

            <table width="100%" class="table table-bordered" style="border-color: black">
                <tbody>
                    <tr>
                        <td style="width: 1%">A</td>
                        <td style="width: 40%">Total no. of papers indexed in SCI/SCIE/SSCI/SJR/ABDC</td>
                        <td style="width: 59%">{{ $ph6_data[0]->no_of_papers_indexed_in_sci ?? '' }}</td>

                    </tr>
                    <tr>
                        <td style="width: 1%">B</td>
                        <td style="width: 40%">Total no. of published Patent/Book Chapter contribution etc.</td>
                        <td style="width: 59%">
                            {{ $ph6_data[0]->no_of_published_patent_book_chapter_contribution ?? '' }}</td>

                    </tr>
                    <tr>
                        <td style="width: 1%">C</td>
                        <td style="width: 40%">Total no. of papers presented in seminar/conferences/others</td>
                        <td style="width: 59%">{{ $ph6_data[0]->total_no_of_papers_presented_seminar ?? '' }}</td>

                    </tr>
                    <tr>
                        <td style="width: 1%">D</td>
                        <td style="width: 99%" colspan="2">
                            <table class="table table-bordered">

                                <thead>
                                    <tr>
                                        <th style="text-align: center"></th>
                                        <th style="text-align: center">SCIE</th>
                                        <th style="text-align: center">SSCI</th>
                                        <th style="text-align: center">SJR-Q1</th>
                                        <th style="text-align: center">ABDC-A*/A</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td style="width: 40%">
                                            Number of papers in which the candidate is the
                                            first/corresponding author</td>
                                        <td style="text-align: center">
                                            {{ $ph6_data[0]->no_of_papers_candidate_author_scie ?? '' }}</td>
                                        <td style="text-align: center">
                                            {{ $ph6_data[0]->no_of_papers_candidate_author_ssci ?? '' }} </td>
                                        <td style="text-align: center">
                                            {{ $ph6_data[0]->no_of_papers_candidate_author_sjrq1 ?? '' }}</td>
                                        <td style="text-align: center">
                                            {{ $ph6_data[0]->no_of_papers_candidate_author_abcd ?? '' }}</td>

                                    </tr>
                                </tbody>
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="text-align: center">Q1</th>
                                        <th style="text-align: center">Q2</th>
                                        <th style="text-align: center">Q3</th>
                                        <th style="text-align: center">Q4</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td style="width: 40%">
                                            Current JCR(WoS) Impact factor of the journals in
                                            which the SCIE / SCIE SJR/ABDC indexed papers
                                            are published</td>
                                        <td style="text-align: center">
                                            {{ $ph6_data[0]->abcd_indexed_papers_are_published_q1 ?? '' }}</td>
                                        <td style="text-align: center">
                                            {{ $ph6_data[0]->abcd_indexed_papers_are_published_q2 ?? '' }} </td>
                                        <td style="text-align: center">
                                            {{ $ph6_data[0]->abcd_indexed_papers_are_published_q3 ?? '' }}</td>
                                        <td style="text-align: center">
                                            {{ $ph6_data[0]->abcd_indexed_papers_are_published_q4 ?? '' }}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </td>


                    </tr>
                </tbody>
            </table>

            <h4 style="text-decoration:underline">11. SDetails of Research Papers published/Accepted by the scholar as
                first/corresponding author based on thesis work:</h4>
            <b>(Attach a copy of the first page of papers SCIE / SCIE SJR/ABDC Indexed Publications)</b>

            <table width="100%" class="table table-bordered" style="border-color: black">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Name of Authors</th>
                        <th>Title</th>
                        <th>Name of Journa</th>
                        <th>Name of Publisher</th>
                        <th>Published/Accepted</th>
                        <th>Year of publication</th>
                        <th>Volume No./Page No</th>
                        <th>SCIE / SCIE SJR/ABDC indexed(YES/NO)</th>
                        <th>ISSN No</th>
                        <th>DOI No.</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=0; @endphp
                    @foreach ($ph6_research_papers_published as $key => $value)
                        @php $i++; @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $value->name_of_auther }}</td>
                            <td>{{ $value->title }}</td>
                            <td>{{ $value->name_of_journal }}</td>
                            <td>{{ $value->name_of_publisher }}</td>
                            <td>{{ $value->published_accepted }}</td>
                            <td>{{ $value->year_of_publication }}</td>
                            <td>{{ $value->page_no }}</td>
                            <td>{{ $value->abcd_indexed }}</td>
                            <td>{{ $value->issn_no }}</td>
                            <td>{{ $value->doi_no }}</td>
                            <td>
                                @if ($value->file)
                                    <a href="<?php echo env('APP_URL', 'http://localhost') . '/' . $value->file; ?>" target="_blank">Download</a>
                                @else
                                    NA
                                @endif
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 >*SCOPUS will be considered on case-to-case basis</h4><br>
           <h4> <b>Attach separate sheet(s) indicating the brief details of Research Work including its originality and novelty as compared to similar works.</b></h4>

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
