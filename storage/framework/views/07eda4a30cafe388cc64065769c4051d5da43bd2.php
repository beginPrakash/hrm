<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- Page Wrapper -->
<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
    <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Reports</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ul>
                </div>
                
            </div>
        </div>           
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="<?php echo e(route('baladiya_report')); ?>">
                    <?php echo csrf_field(); ?>
            
            <input type="hidden" name="type" class="type_val">
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker expiry_date" type="text" name="expiry_date" id="expiry_date" value="<?php echo (isset($search['expiry_date']) && !empty($search['expiry_date'])) ? $search['expiry_date'] : ''; ?>">
                        </div>
                        <label class="focus-label">Expiry From Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker to_date" type="text" name="to_date" id="to_date" value="<?php echo (isset($search['to_date']) && !empty($search['to_date'])) ? $search['to_date'] : ''; ?>">
                        </div>
                        <label class="focus-label">Expiry To Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search company_drp" name="company">
                            <option value="">Select Company</option>
                            <?php foreach ($company as $key => $val) {?>
                                <option value="<?php echo e($key); ?>" <?php echo (isset($search['company']) && $search['company']==$key)?'selected':''; ?>><?php echo e($val); ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Company</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search subcompany_drp" name="subcompany">
                            <option value="">Select SubCompany</option>
                            <?php foreach ($subcompany as $key => $val) {?>
                                <option value="<?php echo e($key); ?>" <?php echo (isset($search['subcompany']) && $search['subcompany']==$key)?'selected':''; ?>><?php echo e($val); ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">SubCompany</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select id="multiple-checkboxes" name="user_ids[]" multiple="multiple"> 
                            <option value="">Select User</option> 
                            <?php if(isset($user_list) && count($user_list) > 0): ?>
                                <?php $__currentLoopData = $user_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($search['user_ids']) && !empty($search['user_ids'])): ?>
                                    <?php
                                    if (in_array($val->id, $search['user_ids'])) { 
                                        $selected = 'selected';
                                    } else { 
                                        $selected = '';
                                    } 
                                    ?>
                                <?php endif; ?>
                                    <option value="<?php echo e($val->id); ?>" <?php echo e($selected ?? ''); ?>><?php echo e($val->first_name); ?> <?php echo e($val->last_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?> 
                        </select>  
                    </div> 
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="branch">
                            <option value="">Select Branch</option>
                            <?php foreach ($branch as $key => $val) {?>
                                <option value="<?php echo e($key); ?>" <?php echo (isset($search['branch']) && $search['branch']==$key)?'selected':''; ?>><?php echo e($val); ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Branch</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="department">
                            <option value="">Select Department</option>
                            <?php foreach ($department as $key => $val) {?>
                                <option value="<?php echo e($key); ?>" <?php echo (isset($search['department']) && $search['department']==$key)?'selected':''; ?>><?php echo e($val); ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Department</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="designation">
                            <option value="">Select Job Title</option>
                            <?php foreach ($designation as $key => $val) {?>
                                <option value="<?php echo e($key); ?>" <?php echo (isset($search['designation']) && $search['designation']==$key)?'selected':''; ?>><?php echo e($val); ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Job Title</label>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-2">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success w-100 search_btn"> Search </button> 
                    </div>  
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="d-grid"> 
                        <button type="submit" class="btn add-btn download_btn"><i class="fa fa-download"></i>Download</button> 
                    </div>  
                </div>
            </div>
        </form>

        <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatablex" id="datatable">
                            <thead>
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

                                <?php $i = 1; $baladiya_cost = 0;?>
                            	<?php if(isset($data_list)): ?>
                            	    <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(isset($data->employee_details) && !empty($data->employee_details)): ?>
                                            <?php $baladiya_cost =  $data->employee_details->baladiya_cost ?? 0;
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
                                            ?>
                                        <?php endif; ?>
                                <tr>
                                    <td>
                                        <?php echo e($i); ?>

                                    </td>
                                    <td><?php echo e($data->emp_generated_id); ?></td>
                                    <td><?php echo e($data->first_name); ?> <?php echo e($data->last_name); ?></td>
                                    <td><?php echo e((isset($data->employee_details) && !empty($data->employee_details)) ? $data->employee_details->c_id : ''); ?></td>
                                    <td><?php echo e((isset($data->employee_designation) && !empty($data->employee_designation)) ? $data->employee_designation->name : ''); ?></td>
                                    <td><?php echo e(date('d, M Y', strtotime($data->joining_date))); ?></td>
                                    <td><?php echo e((isset($data->employee_details) && !empty($data->employee_details->expi_c_id)) ? date('d, M Y', strtotime($data->employee_details->expi_b_id)) : ''); ?></td>
                                    <td><?php echo e((isset($data->employee_residency) && !empty($data->employee_residency)) ? $data->employee_residency->name : ''); ?></td>
                                    <td><?php echo e((isset($data->employee_subcompany) && !empty($data->employee_subcompany)) ? $data->employee_subcompany->name : ''); ?></td>
                                    <td>KWD <?php echo e(number_format($baladiya_cost,2) ?? 0); ?></td>
                                    <td><?php echo e($status); ?></td>
                                </tr>
                                <?php $i++; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

</div>
<!-- /Page Wrapper -->


</div>


</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script> 
<script>
    $(document).ready(function() {
        // $('#multiple-checkboxes').multiselect();
        $('.selectwith_search').select2({
            minimumResultsForSearch: 1,
            width: '100%'
        });

        $('.type_val').val('');
        $('#datatable').DataTable( {
            paging: true,
        } );
    } );

    $("#multiple-checkboxes").select2({
			closeOnSelect : false,
			placeholder : "Select User",
			allowHtml: true,
			allowClear: true,
			tags: true ,
            width: '100%'
		});

    $(document).on('click','.download_btn',function(){
        $('.type_val').val('pdf');
    });

    $(document).on('click','.search_btn',function(){
        $('.type_val').val('');
    });

    $(document).on('change','.company_drp',function(){
        var id= $(this).val();
        var sid= $('.subcompany_drp').val();
        $.ajax({
            url: "<?php echo e(route('listuserbycompany')); ?>",
            type: "POST",
            dataType: "json",
            data: {"_token": "<?php echo e(csrf_token()); ?>", id:id,sid:sid},
            success:function(response)
                {
                    $('#multiple-checkboxes').html(response.res).fadeIn();
                }
        });
    });

    $(document).on('change','.subcompany_drp',function(){
        var sid= $(this).val();
        var id= $('.company_drp').val();
        $.ajax({
            url: "<?php echo e(route('listuserbycompany')); ?>",
            type: "POST",
            dataType: "json",
            data: {"_token": "<?php echo e(csrf_token()); ?>", id:id,sid:sid},
            success:function(response)
                {
                    $('#multiple-checkboxes').html(response.res).fadeIn();
                }
        });
    });
                                            
</script>
<?php /**PATH C:\wamp64_new\www\hrm\resources\views/reports/baladiya.blade.php ENDPATH**/ ?>