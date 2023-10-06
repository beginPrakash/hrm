<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<style type="text/css">
    
</style>
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
                    <?php //echo $salaryCount;//if($salaryCount <= 0){ ?>
                    <div class="col-auto float-end ms-auto">
                        <button class="btn add-btn" data-bs-toggle="modal" data-bs-target="#generateSalaryList" <?php echo ($salaryCount >0)?'disabledx title="Already Generated"':''; ?>><i class="fa fa-plus"></i> Generate Salary List</button>
                    </div>
                    <?php //} ?>
                </div>
            </div>
            
            
            <!-- Search Filter -->
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
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
               <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">To</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                    <a href="#" class="btn btn-success w-100"> Search </a>  
                </div>     
            </div>
            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatablex" id="datatable">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Employee ID</th>
                                    <th>Company</th>
                                    <th>Join Date</th>
                                    <!-- <th>Role</th> -->
                                    <th>Salary</th>
                                    <!-- <th>Overtime Salary</th> -->
                                    <th>Payslip</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php  //echo '<pre>';print_r($employees[0]->employee_residency->name);
                            	if(isset($employees))
                            	{
                            		foreach($employees as $emp)
                            		{
                                        $encodedData = base64_encode(json_encode($emp));
                            		?>

                                <tr>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="profile.php" class="avatar"><img alt="" src="assets/img/profiles/avatar-02.jpg"></a>
                                            <a href="profile.php"><?php echo $emp->first_name; ?> <?php echo (isset($emp->last_name))?$emp->last_name:''; ?> <span><?php echo (isset($emp->employee_designation->name))?': '.$emp->employee_designation->name:''; ?></span></a>
                                        </h2>
                                    </td>
                                    <td><?php echo $emp->emp_generated_id; ?></td>
                                    <td><?php echo (isset($emp->employee_residency->name))?$emp->employee_residency->name:''; ?></td>
                                    <td><?php echo ($emp->joining_date!=NULL)?date('d F, Y', strtotime($emp->joining_date)):''; ?></td>
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
                                    <td><?php echo (isset($emp->employee_salary_details->total_salary))?$emp->employee_salary_details->total_salary:0; ?></td>
                                    <!-- <td><?php //echo $emp->employee_salary_details->total_overtime_salary; ?></td> -->
                                    <td>
                                        <?php if((isset($emp->employee_salary_details->total_salary)) && $emp->employee_salary_details->total_salary > 0){ ?>
                                            <a class="btn btn-sm btn-primary" href="/generate-salary-slip/<?php echo $emp->employee_salary_details->id; ?>">Generate Slip</a>
                                        <?php } ?>
                                    </td>
                                    <td><a class="dropdown-item btn btn-info btn-sm text-white ADbutton" href="#" data-bs-toggle="modal" data-bs-target="#edit_salary_AD" data-data="<?php echo $encodedData; ?>">Additions/Deductions</a></td>
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
        
        <!-- Add Salary Modal -->
        <div id="add_salary" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Staff Salary</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <div class="form-group">
                                        <label>Select Staff</label>
                                        <select class="select"> 
                                            <option>John Doe</option>
                                            <option>Richard Miles</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Net Salary</label>
                                    <input class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <h4 class="text-primary">Earnings</h4>
                                    <div class="form-group">
                                        <label>Basic</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>DA(40%)</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>HRA(15%)</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Conveyance</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Allowance</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Medical  Allowance</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Others</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="add-more">
                                        <a href="#"><i class="fa fa-plus-circle"></i> Add More</a>
                                    </div>
                                </div>
                                <div class="col-sm-6">  
                                    <h4 class="text-primary">Deductions</h4>
                                    <div class="form-group">
                                        <label>TDS</label>
                                        <input class="form-control" type="text">
                                    </div> 
                                    <div class="form-group">
                                        <label>ESI</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>PF</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Leave</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Prof. Tax</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Labour Welfare</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Others</label>
                                        <input class="form-control" type="text">
                                    </div>
                                    <div class="add-more">
                                        <a href="#"><i class="fa fa-plus-circle"></i> Add More</a>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Salary Modal -->
        
        <!-- Edit Salary Modal -->
        <div id="edit_salary" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Staff Salary</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <div class="form-group">
                                        <label>Select Staff</label>
                                        <select class="select"> 
                                            <option>John Doe</option>
                                            <option>Richard Miles</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Net Salary</label>
                                    <input class="form-control" type="text" value="$4000">
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <h4 class="text-primary">Earnings</h4>
                                    <div class="form-group">
                                        <label>Basic</label>
                                        <input class="form-control" type="text" value="$6500">
                                    </div>
                                    <div class="form-group">
                                        <label>DA(40%)</label>
                                        <input class="form-control" type="text" value="$2000">
                                    </div>
                                    <div class="form-group">
                                        <label>HRA(15%)</label>
                                        <input class="form-control" type="text" value="$700">
                                    </div>
                                    <div class="form-group">
                                        <label>Conveyance</label>
                                        <input class="form-control" type="text" value="$70">
                                    </div>
                                    <div class="form-group">
                                        <label>Allowance</label>
                                        <input class="form-control" type="text" value="$30">
                                    </div>
                                    <div class="form-group">
                                        <label>Medical  Allowance</label>
                                        <input class="form-control" type="text" value="$20">
                                    </div>
                                    <div class="form-group">
                                        <label>Others</label>
                                        <input class="form-control" type="text">
                                    </div>  
                                </div>
                                <div class="col-sm-6">  
                                    <h4 class="text-primary">Deductions</h4>
                                    <div class="form-group">
                                        <label>TDS</label>
                                        <input class="form-control" type="text" value="$300">
                                    </div> 
                                    <div class="form-group">
                                        <label>ESI</label>
                                        <input class="form-control" type="text" value="$20">
                                    </div>
                                    <div class="form-group">
                                        <label>PF</label>
                                        <input class="form-control" type="text" value="$20">
                                    </div>
                                    <div class="form-group">
                                        <label>Leave</label>
                                        <input class="form-control" type="text" value="$250">
                                    </div>
                                    <div class="form-group">
                                        <label>Prof. Tax</label>
                                        <input class="form-control" type="text" value="$110">
                                    </div>
                                    <div class="form-group">
                                        <label>Labour Welfare</label>
                                        <input class="form-control" type="text" value="$10">
                                    </div>
                                    <div class="form-group">
                                        <label>Fund</label>
                                        <input class="form-control" type="text" value="$40">
                                    </div>
                                    <div class="form-group">
                                        <label>Others</label>
                                        <input class="form-control" type="text" value="$15">
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Salary Modal -->
        
        <!-- Delete Salary Modal -->
        <div class="modal custom-modal fade" id="delete_salary" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Salary</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
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
        <!-- /Delete Salary Modal -->

        <!-- Generate Salary List Modal -->
        <div class="modal custom-modal fade" id="generateSalaryList" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Generate Salary List</h3>
                            <p>Are you sure want to Generate Salary For <strong><?php echo date('M, Y'); ?></strong>?</p>
                        </div>
                        <div class="modal-btn delete-action" id="loadingDiv">
                            <div class="row">
                                <div class="col-6">
                                    <form action="#" method="post" id="generateSalaryListForm">
                                        <?php echo csrf_field(); ?>
                                        <!-- <input type="hidden" name="type" id="type" value="salary"> -->
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
        <!-- /Generate Salary List Modal -->

        <!-- Edit Salary Additions/Deductions Modal -->
        <div id="edit_salary_AD" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Salary Additions/Deductions</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/salary_add_ded">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="salary_id" id="salary_id">
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <label>Net Salary</label>
                                    <input class="form-control" type="text" value="" name="net_salary" id="net_salary" readonly>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Type</label><br>
                                    <input class="form-controlx adradio" type="radio" value="1" name="addition_deduction" checked> Addition
                                    <input class="form-controlx adradio" type="radio" value="2" name="addition_deduction"> Deduction
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6 addition"> 
                                    <div class="form-group">
                                        <label>Addition</label><?php //echo '<pre>';print_r($additions); ?>
                                        <select class="select" id="addition_drop" name="addition_drop">
                                            <option value="">...Select...</option> 
                                            <?php
                                            if(isset($additions))
                                            {
                                                foreach($additions as $ad)
                                                {
                                                ?>
                                                <option value="<?php echo $ad->id; ?>" data-id="<?php echo $ad->unit_amount; ?>"><?php echo $ad->name; ?></option>
                                                <?php
                                                }
                                            }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 deduction hideit"> 
                                    <div class="form-group">
                                        <label>Deduction</label>
                                        <select class="select" id="deduction_drop" name="deduction_drop">
                                            <option value="">...Select...</option> 
                                            <?php
                                            if(isset($deductions))
                                            {
                                                foreach($deductions as $dd)
                                                {
                                                ?>
                                                <option value="<?php echo $dd->id; ?>" data-id="<?php echo $dd->unit_amount; ?>"><?php echo $dd->name; ?></option>
                                                <?php
                                                }
                                            }?>
                                        </select>
                                    </div>
                                </div>
                            <!-- </div>
                            <div class="row"> --> 
                                <div class="col-sm-6"> 
                                    <label>Change in Salary</label>
                                    <input class="form-control" type="text" value="" name="change_amount" id="change_amount" readonly>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Total Salary</label>
                                    <input class="form-control" type="text" value="" name="net_total_salary" id="net_total_salary" readonly>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Salary Additions/Deductions Modal -->
        
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
            'pdfHtml5'
        ]
    } );
} );
</script><?php /**PATH C:\xampp81\htdocs\hrmumair\resources\views/payroll/employee_salary.blade.php ENDPATH**/ ?>