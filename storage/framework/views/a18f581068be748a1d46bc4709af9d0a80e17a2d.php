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
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered custom-table">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th> 
                                    <th>Company</th>  
                                    <th>License</th>
                                    <th>Salary</th>
                                    <th>Travel Allowance</th>
                                    <th>House Allowance</th>
                                    <th>Position Allowance</th>
                                    <th>Phone Allowance</th>
                                    <th>Other Allowance</th>
                                    <th>Deduction</th>
                                    <th>Total Earning</th>
                                    <th>Cash or Bank</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $comapny_arr = []; ?>
                                <?php if(isset($employee_branch) && count($employee_branch) > 0): ?>
                                    <?php $__currentLoopData = $employee_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bkey => $bval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $i = 1;?>
                                        <?php if(isset($bval->employee_list) && count($bval->employee_list) > 0): ?>
                                            <?php $comapny_arr['branch_name'][$bkey]['name'] = $bval->name; ?>
                                            <tr> 
                                                <td colspan="15">Branch : <?php echo e($bval->name); ?> <td>    
                                            </tr>
                                            <?php $total_c_amount = 0; ?>
                                            <?php $__currentLoopData = $bval->employee_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekey => $eval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                            <?php
                                                    $salary_calc = calculateSalaryByFilter($eval->user_id,$eval->emp_generated_id,$month,$year);
                                                    $total_allowence = calculate_employee_allowence($eval->id); 
                                                ?>  
                                            <?php 
                                            $compant_name = (isset($eval->employee_company_details) && !empty($eval->employee_company_details)) ? $eval->employee_company_details->company_name : '';
                                            $comapny_arr['company_name'][$ekey] = $compant_name;
                                             if (in_array($compant_name, $comapny_arr['company_name'], true)) {
                                                $total_c_amount = $total_c_amount + $salary_calc;
                                             }
                                             $c_total = $total_c_amount + $total_allowence;
                                             $comapny_arr['branch_name'][$bkey]['company_name'][$bkey]['cname']= $compant_name;
                                             $comapny_arr['branch_name'][$bkey]['company_name'][$bkey]['total']= $c_total;

                                            ?> 
                                            
                                                                                
                                                <tr>
                                                    <td>
                                                        <?php echo e($i); ?>

                                                    </td>
                                                    <td><?php echo e($eval->user_id); ?></td>
                                                    <td><?php echo e($eval->first_name); ?> <?php echo e($eval->last_name); ?></td>
                                                    <td><?php echo e((isset($eval->employee_designation) && !empty($eval->employee_designation)) ? $eval->employee_designation->name : ''); ?></td>
                                                    <td><?php echo e((isset($eval->employee_company_details) && !empty($eval->employee_company_details)) ? $eval->employee_company_details->company_name : ''); ?></td>
                                                    <td><?php echo e((isset($eval->employee_details) && !empty($eval->employee_details)) ? $eval->employee_details->license : ''); ?></td>
                                                    <td><?php echo e($salary_calc ?? 0); ?></td>
                                                    <td><?php echo e((isset($eval->employee_salary) && !empty($eval->employee_salary)) ? $eval->employee_salary->travel_allowance : 0); ?></td>
                                                    <td><?php echo e((isset($eval->employee_salary) && !empty($eval->employee_salary)) ? $eval->employee_salary->house_allowance : 0); ?></td>
                                                    <td><?php echo e((isset($eval->employee_salary) && !empty($eval->employee_salary)) ? $eval->employee_salary->position_allowance : 0); ?></td>
                                                    <td><?php echo e((isset($eval->employee_salary) && !empty($eval->employee_salary)) ? $eval->employee_salary->phone_allowance : 0); ?></td>
                                                    <td><?php echo e((isset($eval->employee_salary) && !empty($eval->employee_salary)) ? $eval->employee_salary->other_allowance : 0); ?></td>
                                                    <td>0</td>
                                                    <td><?php echo e($salary_calc + $total_allowence); ?></td>
                                                    <td>Cash</td>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
                $currentMonth = date('F', mktime(0, 0, 0, $month, 10));
            ?>
           
            <!-- <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered custom-table">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th> 
                                    <th>Company</th>  
                                    <th>License</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr align="center">
                                    <td colspan="6"><?php echo e($currentMonth); ?> <?php echo e($year); ?><td>
                                </tr> -->
                                <!-- <tr>
                                    <td></td>
                                    <td colspan="2">Company A</td>
                                    <td colspan="2">Company B</td>
                                </tr>
                                <tr>
                                    <td>Branch</td>
                                    <td>Cash</td>
                                    <td>Bank</td>
                                    <td>Cash</td>
                                    <td>Bank</td>
                                </tr>
                                <tr>
                                    <td>Branch1</td>
                                    <td>0</td>
                                    <td>20</td>
                                    <td>30</td>
                                    <td>40</td>
                                </tr> -->
                                <!-- <tr>
                                    <td></td>
                                    <?php if(isset($comapny_arr) && count($comapny_arr) > 0): ?>
                                        <?php $__currentLoopData = $comapny_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey => $cval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!empty($cval)): ?>
                                        <?php $__currentLoopData = $cval; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tkey => $tval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(!empty($tval['company_name'])): ?>
                                                <?php $__currentLoopData = $tval['company_name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cokey => $coval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <td colspan="2"><?php echo e($coval['cname'] ?? ''); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                            
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tr> -->
                             
                                <!-- <?php if(isset($comapny_arr) && count($comapny_arr) > 0): ?>
                                        <?php $__currentLoopData = $comapny_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey => $cval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!empty($cval)): ?>
                                        <?php $__currentLoopData = $cval; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tkey => $tval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                        <td><?php echo e($tval['name'] ?? 'asa'); ?></td>
                                            <?php if(!empty($tval['company_name'])): ?>
                                                <?php $__currentLoopData = $tval['company_name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cokey => $coval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <td><?php echo e($coval['total'] ?? ''); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                            
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?> -->
                                           
                        <!-- </tbody>
                        </table>
                     
                    </div>
                </div>
            </div> -->
        </div>
        <!-- /Page Content -->
  
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
            //'pdfHtml5'
        ]
    } );
} );

$(document).on('click','.generate_pdf_btn',function(){
    $('#report_type').val('pdf');
    $('#salary_form').submit();
});
</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/payroll/employee_salary_pdf.blade.php ENDPATH**/ ?>