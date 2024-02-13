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
            <h3 class="page-title">Baladeya Reports</h3>
            <table class="custom-table" style="margin-bottom: 30px;max-width:400px;">
                <thead style="background-color: #F5F5F5">
                    <tr>
                    <th>Sr.No</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Civil Id</th>
                    <th>Designation</th>
                    <th>Date Of Joining</th>
                    <th>Expired</th>
                    <th>Company</th>
                    <th>SubCompany</th>
                    <th>Cost</th>
                    <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                            
                    @if(isset($data_list) && count($data_list) > 0)
                        @php $i = 1; $com_arr =[]; $sum = 0; $total = 0; $company = ''; $sumarr=[];@endphp
                        @foreach($data_list as $key => $data)
                            @if(isset($data->employee_details) && !empty($data->employee_details))
                                @php $baladiya_cost =  $data->employee_details->baladiya_cost ?? 0;
                                    $expi_b_id =  $data->employee_details->expi_b_id ?? '';
                                    if(!empty($expi_b_id)):
                                        $exp_str = strtotime($expi_b_id);
                                        $cur_str = strtotime(date('Y-m-d'));
                                        if($exp_str < $cur_str):
                                            $status = 'Expired';
                                        else:
                                            $status = 'Active';
                                        endif;
                                    else:
                                        $status = '';
                                    endif;
                                @endphp
                            @endif
                            <tr>
                                <td>
                                    <h3 class="mb-0">{{$i}}</h3>
                                </td>
                                <td>
                                    <p>{{$data->emp_generated_id}}</p>
                                </td>
                                <td>
                                    <p>{{$data->first_name}} {{$data->last_name}}</p>
                                </td>
                                <td>
                                    <p>{{(isset($data->employee_details) && !empty($data->employee_details)) ? $data->employee_details->c_id : ''}}</p> 
                                </td>
                                <td>
                                    <p>{{(isset($data->employee_designation) && !empty($data->employee_designation)) ? $data->employee_designation->name : ''}}</p>
                                </td>
                                <td>
                                    <p>{{date('d, M Y', strtotime($data->joining_date))}}</p>
                                </td>
                                <td>
                                    <p>{{(isset($data->employee_details) && !empty($data->employee_details->expi_b_id)) ? date('d, M Y', strtotime($data->employee_details->expi_b_id)) : ''}}</p>
                                </td>
                                <td>
                                    <p>{{(isset($data->employee_residency) && !empty($data->employee_residency)) ? $data->employee_residency->name : ''}}</p>
                                </td>
                                <td>
                                    <p>{{(isset($data->employee_subcompany) && !empty($data->employee_subcompany)) ? $data->employee_subcompany->name : ''}}</p>
                                </td>
                                <td>
                                    <p>KWD {{$baladiya_cost ?? 0}}</p>
                                </td>
                                <td>
                                    <p>{{$status}}</p>
                                </td>
                            </tr>
                            @php $i++; @endphp    
                       
                        
                    @endforeach
                    @php $sum = 0; @endphp
                            @if(isset($com_list) && count($com_list) > 0)
                                @foreach($com_list as $val)
                                @php $cost_sum = _sum_of_empcost($val->ids,'baladiya_cost'); 
                                $sum = $sum +$cost_sum; @endphp
                                    <tr>                       
                                        <td colspan="8">
                                            <p align="center">Sub total of</p>
                                        </td>
                                        <td colspna="1">
                                            <p align="center">{{_get_company_name($val->company)}}</p>
                                        </td>
                                        <td colspan = "2">
                                            <p align="center"> KWD {{number_format($cost_sum,2)}}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            <tr>
                                <td colspan="9">
                                    <p align="center">Total</p>
                                </td>
                                <td colspan = "2">
                                    <p align="center"> KWD {{number_format($sum,2)}}</p>
                                </td>
                            </tr>
                            @endif
                    @endif
                </tbody>
            </table>
       
        


    
    </div>
</body>
</html>
<?php //exit; ?>