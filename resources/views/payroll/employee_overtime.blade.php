@include('includes/header')
@include('includes/sidebar')

<div class="main-wrapper">

    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid">
        	
        	@include('flash-message')   
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title"><?php echo ucfirst($title); ?></h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?php echo ucfirst($title); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="begin-filters">
                <!-- Search Filter -->
                <form action="/employee-overtime" class="col-xl-5 df" method="post" id="salary_form">
                    @csrf
                
                    <div class="col-sm-6 col-md-5 col-lg-3 col-xl-3 col-12">  
                            <div class="form-group form-focus">
                                <?php
                                $monthArray = array(
                                    '01'    =>  'Jan', '02' => 'Feb', '03' => 'Mar',
                                    '04'    =>  'Apr', '05' => 'May', '06' => 'Jun',
                                    '07'    =>  'Jul', '08' => 'Aug', '09' => 'Sep',
                                    '10'    =>  'Oct', '11' => 'Nov', '12' => 'Dec',
                                );
                                ?>
                                <select class="select floating" name="month" id="report_month"> 
                                    <option value="">-</option>
                                    <?php foreach($monthArray as $makey => $ma) { ?>
                                        <option value="<?php echo $makey; ?>" <?php echo ($makey==$month)?'selected':''; ?>><?php echo $ma; ?></option>
                                    <?php } ?>
                                </select>
                                <label class="focus-label">Select Month</label>
                            </div>
                        </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 col-12">  
                            <div class="form-group form-focus">
                                <select class="select floating" name="year" id="report_year"> 
                                    <option value="">-</option>
                                    <?php for($y=date('Y');$y>=2015;$y--) { ?>
                                        <option value="<?php echo $y; ?>" <?php echo ($year==$y)?'selected':''; ?>><?php echo $y; ?></option>
                                    <?php } ?>
                                </select>
                                <label class="focus-label">Select Year</label>
                            </div>
                        </div>    
                    <input type="hidden" name="report_type" id="report_type" value="">
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 col-12"> 
                        <button type="submit" class="btn btn-success" name="search" value="search" style="text-transform:none"> Generate Overtime Report </button>    
                    </div> 
                </form>
                <!-- /Search Filter -->
                <div class="other-buttons col-xl-3 ">
                    <form action="/employee-overtime" method="post" id="salary_form">
                        @csrf
                        <input type="hidden" name="report_type" value="pdf">
                        <input type="hidden" name="month" class="pdf_month" value="">
                        <input type="hidden" name="year" class="pdf_year" value="">
                        @if(!empty($is_generate_report))
                            <button type="submit" class="btn btn-success generate_pdf_btn" style="text-transform:none;"> Generate PDF </button>    
                        @endif
                    </form>
                    <form action="/employee-overtime" method="post" id="lock_date_form" class="d-none">
                        @csrf
                        
                        <input type="hidden" name="month" class="pdf_month" value="">
                        <input type="hidden" name="year" class="pdf_year" value="">

                            @if(isset($is_lock_emp_salary_data) && ($is_lock_emp_salary_data == 'lock'))
                            <input type="hidden" name="report_type" class="lock_report_type" value="unlock_data">
                            <button type="button" class="btn btn-success lock_btn" style="text-transform:none;"> UnLock Data</button>    
                            @else
                                @if(!empty($is_generate_report))
                                <input type="hidden" name="lock_report_type" class="lock_report_type" value="lock_data">
                                <button type="button" class="btn btn-success lock_btn" style="text-transform:none;"> Lock Data</button>    
                                @endif
                            @endif
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatablex" id="datatable">
                            <thead>
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
                            	<?php  
                                if(!empty($year) && !empty($month)){
                                $i = 1;
                                //echo '<pre>';print_r($employees[0]->employee_residency->name);
                            	if(isset($employees))
                            	{
                            		foreach($employees as $emp)
                            		{ 
                                        $salary_calc = calculateOvertimeByFilter($emp->user_id,$emp->emp_generated_id,$month,$year);
                                        $bonus = calculateBonusByMonth($emp->id,$month,$year);
                                        $total_earning = $salary_calc['total_salary'] + $bonus;
                                    ?>
                                    
                                <tr>
                                    <td>
                                        {{$i}}
                                    </td>
                                    <td>{{$emp->emp_generated_id}}</td>
                                    <td>{{$emp->first_name}} {{$emp->last_name}}</td>
                                    <td>{{number_format($salary_calc['total_overtime_hours'],1) ?? 0}}</td>
                                    <td>{{number_format($salary_calc['total_salary'],2) ?? 0}}</td>
                                    <td>{{$bonus}}</td>
                                    <td>{{number_format($total_earning,2) ?? 0}}</td>
                                </tr>
                                <?php
                                $i++;
                            		}
                                }
                            	}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->
</div>
<!-- end main wrapper-->

@include('includes/footer')

<script>
    $(document).on('click','.ADbutton',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);//console.log(decodedData);
        $.each(JSON.parse(decodedData), function(key,value){
            // console.log(key);
            // console.log(value);
            if(key=='employee_salary_details')
            {
                //console.log(value.total_salary);
                $("#net_salary").val(value.total_salary);
                $('#salary_id').val(value.id);
            }
        });
    })
</script>

<script type="text/javascript">
    $(document).on('change','.adradio',function(){
        var tt = $(this).val();
        if( tt == 1)
        {
            $('.addition').removeClass('hideit');
            $('.deduction').addClass('hideit');
        }
        else
        {
            $('.deduction').removeClass('hideit');
            $('.addition').addClass('hideit');
        }
    });

    $(document).on('click','.generate_pdf_btn',function(){
        $('#lock_date_form').removeClass('d-none');
    });
</script>

<script type="text/javascript">
$(document).on('change','#addition_drop, #deduction_drop',function(){
    var tt = $("input:radio.adradio:checked").val();
    var net_salary = $("#net_salary").val() || 0;
    var amt = $(this).find(':selected').attr('data-id') || 0;
    var total = (tt == 1)?(parseFloat(net_salary) + parseFloat(amt)):(parseFloat(net_salary) - parseFloat(amt));
    // alert(amt);alert(total);
    $("#change_amount").val(amt);
    $("#net_total_salary").val(total);
});
</script>

<script type="text/javascript">
$("#generateSalaryListForm").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var actionUrl = form.attr('action');
    var month_id = $('#month_id').val();
    var divLoading = '<div class="container"><div class="ring"><h1>Generating...</h1></div></div>';
    
    $('#generateSalaryList').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
      });

    $.ajax({
        type: "GET",
        url: '/generateSalaryList/'+month_id,
        data: form.serialize(), // serializes the form's elements.
        beforeSend: function() {
            // setting a timeout
            $('#loadingDiv').empty().html(divLoading);
        },
        success: function(data)
        {
           location.reload();
        }
    });
    
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#datatable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            //'pdfHtml5'
        ]
    } );

    $('.pdf_month').val($('#report_month').val());
    $('.pdf_year').val($('#report_year').val());
} );

$(document).on('click','.lock_btn',function(){
    var month = $('#report_month').val();
    var year = $('#report_year').val();
    var type = $('.lock_report_type').val();
    $.ajax({
        type: "GET",
        url: '/changeovertimelockpdfstatus/'+month+'/'+year+'/'+type,
        success: function(data)
        {
           if(data.res == 'unlock_data'){
                $('.lock_report_type').val('unlock_data');
                $('.lock_btn').text('UnLock Data');
           }else{
                $('.lock_report_type').val('lock_data');
                $('.lock_btn').text('Lock Data');
           }
           location.reload();
        }
    });
});

    
</script>