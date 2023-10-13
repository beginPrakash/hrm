@include('includes/header')
@include('includes/sidebar')

<style type="text/css">
    
</style>
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
                    <?php //echo $salaryCount;//if($salaryCount <= 0){ ?>
                    <div class="col-auto float-end ms-auto">
                        <button class="btn add-btn" data-bs-toggle="modal" data-bs-target="#generateSalaryList" <?php echo ($salaryCount >0)?'disabledx title="Already Generated"':''; ?>><i class="fa fa-plus"></i> Generate Salary List</button>
                    </div>
                    <?php //} ?>
                </div>
            </div>
            
            
            <!-- Search Filter -->
            <form action="/employee-salary" method="post" id="salary_form">
                @csrf
                <div class="row filter-row">
                   <!-- <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                        <div class="form-group form-focus">
                            <input type="text" name="employee" class="form-control floating" value="{{$empname ?? ''}}">
                            <label class="focus-label">Employee Name</label>
                        </div>
                   </div> -->
                   <!-- <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
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
                   </div> -->
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
                            <select class="select floating" name="month" id="report_month"> 
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
                    <div class="col-auto">  
                        <button type="submit" class="btn btn-success" name="search" value="search" style="text-transform:none"> Generate Salary Report </button>    
                    </div>     
                </div>
            </form>
            <!-- /Search Filter -->
            <form action="/employee-salary" method="post" id="salary_form">
                @csrf
                <input type="hidden" name="report_type" value="pdf">
                <input type="hidden" name="month" id="pdf_month" value="">
                <input type="hidden" name="year" id="pdf_year" value="">
                @if(!empty($is_generate_report))
                    <button type="submit" class="btn btn-success generate_pdf_btn d-none" style="text-transform:none;"> Generate PDF </button>    
                @endif
            <form>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatablex" id="datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Position</th>
                                    <th>Company</th>
                                    <th>License</th>
                                    <th>Basic Salary</th>
                                    <th>Salary</th>
                                    <th>Travel Allowance</th>
                                    <th>House Allowance</th>
                                    <th>Position Allowance</th>
                                    <th>Phone Allowance</th>
                                    <th>Food Allowance</th>
                                    <th>Other Allowance</th>
                                    <th>Deduction</th>
                                    <th>Total Earning</th>
                                    <th>Cash or Bank</th>
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
                                        $salary_calc = calculateSalaryByFilter($emp->user_id,$emp->emp_generated_id,$month,$year);
                                        $total_allowence = calculate_employee_allowence($emp->id);
                            		?>
                                    
                                <tr>
                                    <td>
                                        {{$i}}
                                    </td>
                                    <td>{{$emp->emp_generated_id}}</td>
                                    <td>{{$emp->first_name}} {{$emp->last_name}}</td>
                                    <td>{{(isset($emp->employee_branch) && !empty($emp->employee_branch)) ? $emp->employee_branch->name : ''}}</td>
                                    <td>{{(isset($emp->employee_designation) && !empty($emp->employee_designation)) ? $emp->employee_designation->name : ''}}</td>
                                    <td>{{(isset($emp->employee_company_details) && !empty($emp->employee_company_details)) ? $emp->employee_company_details->company_name : ''}}</td>
                                    <td>{{(isset($emp->employee_details) && !empty($emp->employee_details)) ? $emp->employee_details->license : ''}}</td>
                                    <td>{{(isset($emp->employee_salary) && !empty($emp->employee_salary)) ? $emp->employee_salary->basic_salary : 0}}</td>
                                    <td>{{$salary_calc ?? 0}}</td>
                                    <td>{{(isset($emp->employee_salary) && !empty($emp->employee_salary)) ? $emp->employee_salary->travel_allowance : 0}}</td>
                                    <td>{{(isset($emp->employee_salary) && !empty($emp->employee_salary)) ? $emp->employee_salary->house_allowance : 0}}</td>
                                    <td>{{(isset($emp->employee_salary) && !empty($emp->employee_salary)) ? $emp->employee_salary->position_allowance : 0}}</td>
                                    <td>{{(isset($emp->employee_salary) && !empty($emp->employee_salary)) ? $emp->employee_salary->phone_allowance : 0}}</td>
                                    <td>{{(isset($emp->employee_salary) && !empty($emp->employee_salary)) ? $emp->employee_salary->food_allowance : 0}}</td>
                                    <td>{{(isset($emp->employee_salary) && !empty($emp->employee_salary)) ? $emp->employee_salary->other_allowance : 0}}</td>
                                    <td>0</td>
                                    <td>{{$salary_calc + $total_allowence}}</td>
                                    <td>Cash</td>
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
                                        @csrf
                                        <!-- <input type="hidden" name="type" id="type" value="salary"> -->
                                        <!-- <select class="form-control" name="month_id" id="month_id">
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                        </select> -->
                                        <select class="form-control" name="month_id" id="month_id">
            <?php
            $currentMonth = date("n"); // Get the current month (numeric format)
            
            for ($i = 1; $i <= 12; $i++) {
                $monthName = date("F", mktime(0, 0, 0, $i, 1)); // Get the full month name
                echo "<option value='$i'>$monthName</option>";
            }
            ?>
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
                            @csrf
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

    $('#pdf_month').val($('#report_month').val());
    $('#pdf_year').val($('#report_year').val());
} );

    
</script>