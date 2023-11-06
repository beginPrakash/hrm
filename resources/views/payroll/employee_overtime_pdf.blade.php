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
    <table class="custom-table" style="margin-bottom: 30px;max-width:400px;">
                <thead style="background-color: #F5F5F5">
                    <tr>
                    <th>Sr.No</th>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Overtime Hours</th>
                    <th>Overtime Amount</th>
                    <th>Bonus</th>
                    <th>Total Earning</th>
                    </tr>
                </thead>
                <tbody>
                            
                    @if(isset($emp_overtime_data) && count($emp_overtime_data) > 0)
                        @php $i = 1; @endphp
                        
                        @foreach($emp_overtime_data as $key => $val)
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
                                        <p>{{ $val->total_overtime_hours ?? 0}}</p> 
                                    </td>
                                    <td>
                                        <p>{{ number_format($val->overtime_amount, 2) }}</p>
                                    </td>
                                    <td>
                                        <p>bonus</p>
                                    </td>
                                    <td>
                                        <p>{{ number_format($val->total_earning, 2) }}</p>
                                    </td>
                                </tr>
                                @php $i++; @endphp    
                            
                        @endforeach
                    @endif
               
                </tbody>
            </table>
    </div>
</body>
</html>
<?php //exit; ?>