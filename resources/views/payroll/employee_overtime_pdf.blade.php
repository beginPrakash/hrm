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
    @if(isset($emp_branch_data) && count($emp_branch_data) > 0)
        @foreach($emp_branch_data as $bkey => $bval)
            <table style="margin-bottom: 30px;max-width:400px;">
                <tbody>
                    
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Branch: </b>{{$bval->branch_name}}
                            </p>
                        </th>
                    </tr>
                </tbody>
            </table>
             
            <table class="custom-table" style="margin-bottom: 30px;max-width:400px;">
                <thead style="background-color: #F5F5F5">
                    <tr>
                        <th>S.no</th>
                        <th>Emp.Id</th>
                        <th>Name</th>
                        <th>Salary</th>
                        <th>Food Allowance</th>
                        <th>Addition of all other Allowances</th>
                        <th>Bonus</th>
                        <th>Total Earning</th>
                    </tr>
                </thead>
                <tbody>
                            
                @if(isset($emp_salary_data) && count($emp_salary_data) > 0)
                    @php $i = 1; @endphp
                    
                    @foreach($emp_salary_data as $key => $val)
                    @php $total_allowence = total_allowence_withput_food($val->id); @endphp
                        @if($val->branch_name == $bval->branch_name)
                        @php $bonus = calculateBonusByMonth($val->employee_id,$month,$year);
                                        $total_earning = $val->overtime_amount + $val->manual_ot_amount + $bonus;
                                        $total_sal = $val->overtime_amount + $val->manual_ot_amount;
                                        @endphp
                            <tr>
                                <td>
                                    <h3 class="mb-0">{{$i}}</h3>
                                </td>
                                <td>
                                    <p>{{$val->employee_id}}</p>
                                </td>
                                <td>
                                    <p>{{$val->name}}</p>
                                </td>
                                <td>
                                    <p>{{ number_format($total_sal, 2) }}</p> 
                                </td>
                                <td>
                                    <p>{{$val->food_allowence ?? 0}}</p>
                                </td>
                                <td>
                                    <p>{{$total_allowence ?? 0}}</p>
                                </td>
                                <td>
                                    <p>{{$bonus}}</p>
                                </td>
                                <td>
                                    <p>{{ number_format($val->total_earning, 2) }}</p>
                                </td>
                            </tr>
                            @php $i++; @endphp    
                         @endif  
                        
                    @endforeach
                @endif
               
                        </tbody>
            </table>
        @endforeach  
        
        @php $currentMonth = date('F', mktime(0, 0, 0, $month, 10)); @endphp
        <div style="font-size: 20px; margin-bottom:10px; font-weight: bold;">Summary of {{$currentMonth}} {{$year}}</div>

        <table class="table table-bordered custom-table">
            <tbody> 
                <tr>
                    <td>Branch</td>
                    @if(isset($emp_company_data) && count($emp_company_data) > 0)
                        @foreach($emp_company_data as $ckey => $cval)
                            <td colspan="2">{{$cval->company_name}}</td>
                        @endforeach
                    @endif
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    @if(isset($emp_company_data) && count($emp_company_data) > 0)
                        @foreach($emp_company_data as $ckey => $cval)
                            <td>Cash</td>
                            <td>Bank</td>
                        @endforeach
                    @endif
                    <td>Total</td>
                </tr>
                    
                @if(isset($emp_branch_data) && count($emp_branch_data) > 0)
                    @foreach($emp_branch_data as $bkey => $bval)
                        <tr>
                            <td>{{$bval->branch_name}}</td>
                            @if(isset($emp_company_data) && count($emp_company_data) > 0)
                                @foreach($emp_company_data as $ckey => $cval)
                                    <td>{{ number_format(calcualte_ot_total_earning_by_month_company($month,$year,$cval->company_id,$bval->id,'cash'), 2) }}</td>
                                    <td>{{calcualte_ot_total_earning_by_month_company($month,$year,$cval->company_id,$bval->id,'bank')}}</td>
                                @endforeach
                            @endif
                            <td>{{ number_format(calcualte_ot_total_earning_by_month_company($month,$year,$cval->company_id,$bval->id,'','total'), 2) }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td>Total</td>
                    @if(isset($emp_company_data) && count($emp_company_data) > 0)
                        @foreach($emp_company_data as $ckey => $cval)
                            <td>{{ number_format(calcualte_ot_total_by_month_company($month,$year,$cval->company_id,'cash'), 2) }}</td>
                            <td>{{ number_format(calcualte_ot_total_by_month_company($month,$year,$cval->company_id,'bank'), 2) }}</td>
                        @endforeach
                    @endif
                    <td></td>
                </tr>
            </tbody>
        </table>
        <table style="margin-top: 20px;">
                <tbody>
                <tr>
                    <td><b>HR Manager Sign.</b></td>
                    <td><b>Sr. Accountant Sign.</b></td>
                    <td><b>Finanace Manager Sign.</b></td>
                    <td><b>General Manager Sign.</b></td>
                    <td><b>CEO Sign.</b></td>
                </tr>
                </tbody>
        </table>


    @endif
    </div>
</body>
</html>
<?php //exit; ?>