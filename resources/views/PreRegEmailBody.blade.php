<!DOCTYPE html>

<html>

<head>

    <title>IIT(ISM) - Pre-Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</head>

<body
    style="font-family: var(--bs-font-sans-serif);font-size: 1rem;font-weight: 400;line-height: 1.5;    color: #212529;-webkit-text-size-adjust: 100%;-webkit-tap-highlight-color: transparent;">


    <div class="container mt-3"
        style="margin-top: 1rem!important;max-width: 1320px;width: 100%;padding-right: var(--bs-gutter-x,.75rem);padding-left: var(--bs-gutter-x,.75rem);margin-right: auto;margin-left: auto;">
        <div class="card"
            style="padding:20px;position: relative;display: flex;flex-direction: column;min-width: 0;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: 0.25rem;">
            <div class="card-body">
                <p>Dear {{$mailData['stu_details'][0]->stu_name}},</p>

                <p>Please find your pre-registration details on this
                    email body for Winter (2022-2023). Please check details of your pre-registration.
                    In case of any discrepancy, please contact the following emails ids within 24
                    hours of the receipt of this email:</p>

                <p>For UG Students: <b>reg_ug@iitism.ac.in</b></p>
                <p>For PG Students: <b>reg_pg@iitism.ac.in</b></p>
                <p>For Ph.D Students: <b>reg_phd@iitism.ac.in</b></p>

                <h4>This email will serve as a
                    proof of the pre-registration of Winter (2022- 2023). Please keep it safe and
                    produce as and when required.</h4>
                <hr>
                <h5 style="text-decoration: underline;">Your Basic Information : </h5>

                <div class="row">
                    <div class="col-md-3">
                        <p> Admission No : <b> {{$mailData['stu_details'][0]->admn_no}} </b>
                        <p>
                    </div>
                    <div class="col-md-3">
                        <p> Name : <b> {{$mailData['stu_details'][0]->stu_name}} </b>
                        <p>
                    </div>
                    <div class="col-md-3">
                        <p> Department : <b> {{$mailData['stu_details'][0]->dept_name}} </b>
                        <p>
                    </div>
                    <div class="col-md-3">
                        <p> Program : <b> {{$mailData['stu_details'][0]->course_name}} </b> </p>
                    </div>
                    <div class="col-md-3">
                        <p> Branch : <b> {{$mailData['stu_details'][0]->branch_name}} </b> </p>
                    </div>
                    <div class="col-md-3">
                        <p> Semester : <b> {{$mailData['stu_details'][0]->current_sem + 1 }} </b> </p>
                    </div>
                </div>
                <hr>
                <h5 style="text-decoration: underline;">Pre-Registration Course Details : </h5>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped"
                            style="text-align: center;font-family: Arial, Helvetica, sans-serif; border-collapse: collapse;width: 100%;">
                            <thead>
                                <tr>
                                    <th
                                        style=" border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px;text-align: left; background-color: #607d8b; color: white;">
                                        Sl. No</th>
                                    <th
                                        style=" border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px;text-align: left; background-color: #607d8b; color: white;">
                                        Course Code</th>
                                    <th
                                        style=" border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px;text-align: left; background-color: #607d8b; color: white;">
                                        Course Name</th>
                                    <th
                                        style=" border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px;text-align: left; background-color: #607d8b; color: white;">
                                        Course Category</th>
                                    <th
                                        style=" border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px;text-align: left; background-color: #607d8b; color: white;">
                                        Priority</th>
                                </tr>
                            </thead>
                            @php $i=0; @endphp
                            <tbody>
                                @foreach($mailData['reg_data'] as $key=>$value)
                                @php $i++; @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$value->subject_code}}</td>
                                    <td>{{$value->subject_name}}</td>
                                    <td>{{$value->sub_category}}</td>
                                    <td>{{$value->priority}}</td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
                <hr>
                <p>Thank you</p>
            </div>
        </div>
    </div>

</body>

</html>