<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<head>


    <style type="text/css">
    body {
    font-family: "CircularStd", sans-serif;
    font-size: 15px;
    }
   
        *{box-sizing:border-box;}
        .color-white{ color:white;}
        .color-2b5da7{ color: #2b5da7; }
        .color-0791bb{ color: #0791bb; }
        .color-5b9bd5{ color: #5b9bd5; }
        .color-bb0707{ color: #bb0707; }
        .bg-color-2b5da7 { background-color: #2b5da7; }
        .bg-color-0799c3 { background-color: #0799c3; }
        .bg-color-5b9bd5 { background-color: #5b9bd5; }
        img { max-width: 100%; }
        .text-center{text-align:center;}
        .text-right{text-align:right;}
        .text-left{text-align:left;}
        .mb-0 {margin-bottom: 0 !important;}


        /************************************ */
        .logo-wrap{text-align:center;}
        table{width:100%;border-collapse:collapse;}
        table tr th,
        table tr td {padding:5px 10px;text-align:left;}
        .custom-table th {padding: 5px 10px; font-size: 16px; }
        .custom-table tr {border-bottom: 1px solid #dee2e6;}
        .custom-table h3 {font-size: 14px;line-height: 16px; margin-bottom: 0; }

        .table-bordered{border:1px solid #dee2e6;}
        .table-bordered th,
        .table-bordered td{padding:3px 10px; border:1px solid #dee2e6;}
        .card{ position:relative;margin-top:25px;margin-bottom:25px;padding:20px;border-radius:25px;border:2px solid #0C0F1E;}
    </style>
</head>
<body>
    <div style="display: block; margin:0 auto;">
            <table style="margin-bottom: 30px;max-width:400px;">
                <tbody>
                    
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-center mb-0">
                                <b>{{(isset($employee->employee_company_details) && !empty($employee->employee_company_details)) ? $employee->employee_company_details->company_name : ''}} </b>
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-center mb-0">
                                <b>Vacation settlement ({{date('d M y', strtotime($leave_data->updated_at))}}) </b>
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-center mb-0">
                                <b>{{$employee->full_name ?? ''}} - {{$employee->emp_generated_id ?? ''}} - {{$employee->employee_details ? $employee->employee_details->c_id : '--'}}</b>
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Salary of employee : </b>{{number_format($leave_data->basic_salary,2) ?? 0}} KWD
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Number of leave in bucket : </b>{{$leave_data->claimed_annual_days + $leave_data->claimed_annual_days_rem}}
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Number of leave approved : </b>{{$leave_data->claimed_annual_days ?? ''}}
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Date of leaves approved  : </b>{{date('d M y', strtotime($leave_approve_date))}}
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>No of leave remaining  : </b>{{$leave_data->claimed_annual_days_rem ?? ''}}
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Number of leaved paying : </b>{{number_format(_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_annual_days ?? 0),2)}} KWD
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Balance amount remains : </b>{{number_format(_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_annual_days_rem ?? 0),2)}} KWD
                            </p>
                        </th>
                    </tr>

                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>No of public holidays : </b>{{$leave_data->claimed_public_days ?? 0 + $leave_data->claimed_public_days_rem ?? 0}}
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>No of public holidays paying : </b>{{number_format(_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_public_days ?? 0),2)}} KWD
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>No of public holidays Remains  : </b>{{$leave_data->claimed_public_days_rem ?? 0}}
                            </p>
                        </th>
                    </tr>

                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Payable amount  : </b>{{number_format((_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_annual_days ?? 0)) + (_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_public_days ?? 0)),2)}} KWD
                            </p>
                        </th>
                    </tr>

                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Number of leaved paying  : </b>{{$leave_data->claimed_annual_days + $leave_data->claimed_public_days}}
                            </p>
                        </th>
                    </tr>

                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>No of public holidays Remains   : </b>{{$employee->public_holidays_balance ?? 0}}
                            </p>
                        </th>
                    </tr>

                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Total Payable amount  : </b>{{number_format((_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_annual_days ?? 0)) + (_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_public_days ?? 0)),2)}} KWD
                            </p>
                        </th>
                    </tr>
                </tbody>
            </table>
            <table style="margin-top: 20px;">
                <tbody>
                <tr>
                    <td><b>HR Sign.</b></td>
                    <td><b>Finanace Sign.</b></td>
                    <td><b>GM Sign.</b></td>
                </tr>
                </tbody>
            </table>
        
    </div>
</body>
</html>
<?php //exit; ?>