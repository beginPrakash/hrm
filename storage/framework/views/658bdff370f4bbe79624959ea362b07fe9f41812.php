<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="main-wrapper">

    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid">
        	
        	<?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
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
                    <?php //echo $overtimeCount;//if($overtimeCount <= 0){ ?>
                    <div class="col-auto float-end ms-auto">
                        <button class="btn add-btn" data-bs-toggle="modal" data-bs-target="#generateOvertimeList" <?php echo ($overtimeCount >0)?'disabled title="Already Generated"':''; ?>><i class="fa fa-plus"></i> Generate Overtime List</button>
                    </div>
                    <?php //} ?>
                </div>
            </div>
            
            
            <!-- Search Filter -->
            <form action="/employee-overtime" method="post">
                <?php echo csrf_field(); ?>
                <div class="row filter-row">
                   <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating">
                            <label class="focus-label">Employee Name</label>
                        </div>
                   </div>
                   <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                        <div class="form-group form-focus select-focus">
                            <select class="select floating"> 
                                <option value=""> -- Select -- </option>
                                <option value="">Employee</option>
                                <option value="1">Manager</option>
                            </select>
                            <label class="focus-label">Role</label>
                        </div>
                   </div>
                   <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12"> 
                        <div class="form-group form-focus select-focus">
                            <select class="select floating"> 
                                <option> -- Select -- </option>
                                <option> Pending </option>
                                <option> Approved </option>
                                <option> Rejected </option>
                            </select>
                            <label class="focus-label">Leave Status</label>
                        </div>
                   </div>
                   <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                        <div class="form-group form-focus">
                            <?php
                            $monthArray = array(
                                '01'    =>  'Jan', '02' => 'Feb', '03' => 'Mar',
                                '04'    =>  'Apr', '05' => 'May', '06' => 'Jun',
                                '07'    =>  'Jul', '08' => 'Aug', '09' => 'Sep',
                                '10'    =>  'Oct', '11' => 'Nov', '12' => 'Dec',
                            );
                            ?>
                            <select class="select floating" name="month"> 
                                <option value="">-</option>
                                <?php foreach($monthArray as $makey => $ma) { ?>
                                    <option value="<?php echo $makey; ?>" <?php echo ($makey==$month)?'selected':''; ?>><?php echo $ma; ?></option>
                                <?php } ?>
                            </select>
                            <label class="focus-label">Select Month</label>
                        </div>
                    </div>
                   <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                        <div class="form-group form-focus">
                            <select class="select floating" name="year"> 
                                <option value="">-</option>
                                <?php for($y=date('Y');$y>=2015;$y--) { ?>
                                    <option value="<?php echo $y; ?>" <?php echo ($year==$y)?'selected':''; ?>><?php echo $y; ?></option>
                                <?php } ?>
                            </select>
                            <label class="focus-label">Select Year</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                        <button type="submit" class="btn btn-success" name="search" value="search"> Search </button>    
                    </div>     
                </div>
            </form>
            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatablex" id="datatable">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <!-- <th>Employee ID</th> -->
                                    <!-- <th>Company</th> -->
                                    <th>Join Date</th>
                                    <th>Month</th>
                                    <!-- <th>Salary</th> -->
                                    <th>Overtime Salary</th>
                                    <th>Payslip</th>
                                    <!-- <th class="text-end">Action</th> -->
                                    <!-- <th class="text-end">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            	<?php  //echo '<pre>';print_r($employees[0]->employee_residency->name);
                            	if(isset($overtimeDetails))
                                {
                                    foreach($overtimeDetails as $sal)
                                    {
                                        $encodedData = base64_encode(json_encode($sal));
                            		?>

                                <tr>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="profile.php" class="avatar"><img alt="" src="assets/img/profiles/avatar-02.jpg"></a>
                                            <a href="profile.php"><?php echo ucfirst($sal->employees->first_name); ?> <?php echo (isset($sal->employees->last_name))?ucfirst($sal->employees->last_name):''; ?> (EMP ID:<?php echo $sal->emp_generated_id; ?>)     <span><?php echo (isset($sal->employee_designation[0]->name))?': '.ucwords($sal->employee_designation[0]->name):''; ?></span>
                                                <br>
                                                <small style="margin-left: 46px;font-size: 12px;"><?php echo (isset($sal->employee_residency[0]->name))?$sal->employee_residency[0]->name:''; ?></small>
                                            </a>
                                        </h2>
                                    </td>
                                    <!-- <td><?php //echo $sal->emp_generated_id; ?></td> -->
                                    <!-- <td><?php //echo (isset($sal->employee_residency->name))?$sal->employee_residency->name:''; ?></td> -->
                                    <td><?php echo ($sal->joining_date!=NULL)?date('d F, Y', strtotime($sal->joining_date)):''; ?></td>
                                    <td><?php echo (isset($sal->employee_salary_details->es_month))?date('F', strtotime($sal->employee_salary_details->es_month)):date('F'); ?></td>
                                    <!-- <td>
                                        <div class="dropdown">
                                            <a href="" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Web Designer </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#">Software Engineer</a>
                                                <a class="dropdown-item" href="#">Software Tester</a>
                                                <a class="dropdown-item" href="#">Frontend Developer</a>
                                                <a class="dropdown-item" href="#">UI/UX Developer</a>
                                            </div>
                                        </div>
                                    </td> -->
                                    <!-- <td><?php //echo $sal->employee_salary_details->total_salary; ?></td> -->
                                    <td>KWD <?php echo (isset($sal->employee_salary_details->total_overtime_salary))?$sal->employee_salary_details->total_overtime_salary:0; ?></td>
                                    <td>
                                        <?php if((isset($sal->employee_salary_details->total_overtime_salary)) && $sal->employee_salary_details->total_overtime_salary > 0){ ?>
                                            <a class="btn btn-sm btn-primary" href="/generate-overtime-slip/<?php echo $sal->employee_salary_details->id; ?>">Generate Slip</a>
                                        <?php } ?>
                                    </td>
                                    
                                    <!-- <td class="text-end">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_salary"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_salary"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td> -->
                                </tr>
                                <?php
                            		}
                            	}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
        <!-- Generate Overtime List Modal -->
        <div class="modal custom-modal fade" id="generateOvertimeList" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Generate Overtime List</h3>
                            <p>Are you sure want to Generate Overtime For <strong><?php echo date('M, Y'); ?></strong>?</p>
                        </div>
                        <div class="modal-btn delete-action" id="loadingDiv">
                            <div class="row">
                                <div class="col-6">
                                    <form action="#" method="post" id="generateOvertimeListForm">
                                        <?php echo csrf_field(); ?>
                                        <!-- <input type="hidden" name="month_id" id="month_id" value="<?php //echo date('m'); ?>"> -->
                                        <select class="form-control" name="month_id" id="month_id">
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-large continue-btn" style="width: 100%;">Generate</button>
                                  </form>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Generate Overtime List Modal -->

    </div>
    <!-- /Page Wrapper -->
</div>
<!-- end main wrapper-->

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
$("#generateOvertimeListForm").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var actionUrl = form.attr('action');
    var month_id = $('#month_id').val();
    var divLoading = '<div class="container"><div class="ring"><h1>Generating...</h1></div></div>';
    
    $('#generateOvertimeList').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
      });

    $.ajax({
        type: "GET",
        url: '/generateOvertimeList/'+month_id,
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
            'pdfHtml5'
        ]
    } );
} );
</script><?php /**PATH /home/eqb1fxfgkdl8/public_html/hrm/resources/views/payroll/employee_overtime.blade.php ENDPATH**/ ?>