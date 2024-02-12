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
        hr {
  border: 0;
  clear:both;
  display:block;
  width: 96%;               
  background-color:#FFFF00;
  height: 1px;
}

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
                            <p class="text-left mb-0">
                            <!-- <img alt="" src="{{$user_img ?? ''}}"> -->
                            </p>
                        </th>
                    </tr>
                    <tr width="80px" style="border:1px solid #dee2e6"></tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                        <h3 class="user-name m-t-0 mb-0">{{$user->first_name ? ucfirst($user->first_name).' '.ucfirst($user->last_name)  : "--"}}
                                                <?php echo ($user->status=='resigned')?'<span class="badge bg-inverse-danger">Deactivated</span>':'<span class="badge bg-inverse-success">Active</span>'; ?>
                                            </h3>
                                            
                                            <h6 class="text-muted">
                                                {{$user->employee_designation ? ucfirst($user->employee_designation->name) : ""}}
                                                {{$user->employee_department ?'('. ucfirst($user->employee_department->name).')' : ""}}
                                            </h6>

                                            <small class="text-muted">{{$user->employee_company ? $user->employee_company->name : ""}}</small>
                                            <div class="staff-id">Branch : {{(isset($user->employee_branch))?$user->employee_branch->name:''}}</div>
                                            <div class="staff-id">Employee ID :{{$user->emp_generated_id ? $user->emp_generated_id : "--"}}</div>
                                            <div class="small doj text-muted">Date of Join : {{$user->joining_date ? dateDisplayFormat($user->joining_date) : "--"}}</div>
                                            <div class="staff-id">Civil Id : {{isset($user->employee_details->c_id) ? $user->employee_details->c_id : " --"}}</div>
                        </th>
                    </tr>
                </tbody>
            </table>
            <table style="margin-bottom: 30px;max-width:400px;">
                <tbody>
                    
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Annual Leaves
                            </p>
                        </th>
                    </tr>
                    <tr width="80px" style="border:1px solid #dee2e6"></tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0" style="font-weight:100;">
                                <b>Total Leave Days: </b><?php echo (isset($annualleavedetails))?$annualleavedetails['totalLeaveDays']:0; ?>
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0" style="font-weight:100;">
                                <b>Used Leave Days: </b>{{$user->used_leave ?? 0}}
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0" style="font-weight:100;">
                            @php $cal_leave = (isset($annualleavedetails) && $annualleavedetails['totalLeaveDays']>0 )?$annualleavedetails['totalLeaveDays']:0; 
                                                            $used_leave = $user->used_leave ?? 0;
                                                            $bal_leave = $cal_leave - $used_leave;@endphp
                                <b>Leave Balance Days: </b>{{$bal_leave ?? 0}}
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0" style="font-weight:100;">
                                <b>Leave Balance Amount: </b>
                                @php $e_sal = (isset($user->employee_salary) && !empty($user->employee_salary)) ? $user->employee_salary->basic_salary : 0; 
                                $em_sal = _calculate_salary_by_days($e_sal,$bal_leave ?? 0); @endphp
                                                            KWD {{number_format(_calculate_salary_by_days($e_sal,$bal_leave ?? 0),2)}}
                            </p>
                        </th>
                    </tr>
                    <tr width="80px" style="border:1px solid #dee2e6">
                    </tr>
                    <tr width="80px" style="border:1px solid #dee2e6"></tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Public Holidays
                            </p>
                        </th>
                    </tr>
                    <tr width="80px" style="border:1px solid #dee2e6"></tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0" style="font-weight:100;">
                                <b>Worked Days: </b><?php echo (isset($user->public_holidays_balance))?$user->public_holidays_balance:0; ?>
                            </p>
                        </th>
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0" style="font-weight:100;">
                            @php 
                            $bal = 0;
                                                $days = $user->public_holidays_balance ?? 0;
                                                $sal = $user->employee_salary ?$user->employee_salary->basic_salary : 0;
                                                $bal = _calculate_salary_by_days($sal,$days);
                                                @endphp
                                <b>Balance Amount: </b>KWD {{number_format($bal,2)}}
                            </p>
                        </th>
                    </tr>

                    <tr width="80px" style="border:1px solid #dee2e6">
                        
                    </tr>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Earning Summary
                            </p>
                        </th>
                    </tr>
                </tbody>
            </table>
            <table class="custom-table" style="margin-bottom: 30px;max-width:400px;border:1px solid #dee2e6;">
                <?php
                $addtot = 0;
                $dedtot = 0; 
                $totsalary = 0;
                $totIndemnity = 0;
                    $totsalary = $salaryDetails->total_salary ?? 0;
                    if(!empty($additions) && isset($additions))
                    {
                        foreach($additions as $addkey=>$add)
                        {
                            $addtot += $add->entry_value;
                        ?>
                        <?php
                        }
                    }
                    
                    if(!empty($deductions) && isset($deductions))
                    {
                        foreach($deductions as $dedkey=>$ded)
                        {
                            $dedtot += $ded->entry_value;
                        ?>
                        <?php
                        }
                    }
                    $totIndemnity = (!empty($indemnityDetails) && count($indemnityDetails) > 0)?$indemnityDetails[count($indemnityDetails)-1]->total_amount:0;
                    $totadditions = (float)$addtot + $em_sal + $bal;
                    $total_overtime_salary = (isset($salaryDetails->total_overtime_salary) && $salaryDetails->total_overtime_salary >0)?$salaryDetails->total_overtime_salary:0;
                    $totpayable = ($totsalary + (float)$addtot + $total_overtime_salary + $totIndemnity + $em_sal + $bal) - (float)$dedtot;

                ?>
                <tbody>
                    <tr>
                        <td>                        
                            <p>
                                <b>Total Salary: </b>KWD <?php echo number_format($totsalary, 2); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>                        
                            <p>
                                <b>Total Bonus: </b>KWD <?php echo number_format($addtot, 2); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>                        
                            <p>
                                <b>Total Overtime: </b>KWD {{number_format($total_overtime_salary, 2)}}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>                        
                            <p>
                                <b>Total Indemnity: </b>KWD <?php echo number_format($totIndemnity,2); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>                        
                            <p>
                                <b>Total Annual Leave: </b>KWD {{number_format(_calculate_salary_by_days($e_sal,$bal_leave ?? 0),2)}}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>                        
                            <p>
                                <b>Total Public Holiday: </b>KWD {{number_format($bal,2)}}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>                        
                            <p>
                                <b>Total Deductions: </b>KWD <?php echo number_format($dedtot, 2); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>                        
                            <p>
                                <b>Total Payable: </b>KWD <?php echo number_format($totpayable, 2); ?>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="custom-table" style="margin-bottom: 30px;max-width:400px;">
                <tbody>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Net Payable : </b>KWD <?php echo number_format($totpayable, 2); ?> (<?php echo ucwords(numberToWords($totpayable)); ?>)
                            </p>
                        </th>
                    </tr>
                </tbody>
            </table>

    </div>
</body>
</html>
<?php //exit; ?>