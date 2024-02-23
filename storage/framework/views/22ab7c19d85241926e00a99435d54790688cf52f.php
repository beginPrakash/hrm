<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- Page Wrapper -->
<!-- Page Wrapper -->
<link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>

<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
        <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Daily Sales</h3>
                </div>
            </div>
        </div>    
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="<?php echo e(route('store_daily_sales.list')); ?>" id="search_form">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col">
                    <h3 class="page-title"><?php echo e((isset($user->employee_branch) && !empty($user->employee_branch->name)) ? $user->employee_branch->name : ''); ?></h3>    
                </div>
                
                <div class="col-sm-6 col-md-2">  
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text" name="search_date" id="search_date" value="<?php echo (isset($search['from_date']))?$search['from_date']:date('d-m-Y', strtotime($startDate ?? '')); ?>">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" 
                                    id="dropdownMenu1" data-toggle="dropdown" 
                                    aria-haspopup="true" aria-expanded="true">
                                Select Selling Period
                            
                            </button>
                            <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
                                <?php if(isset($sells_p_data) && count($sells_p_data) > 0): ?>  
                                    <?php $__currentLoopData = $sells_p_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                        <label>
                                            <input type="checkbox" class="sells_check select_change" name="sells_id[]" value="<?php echo e($key); ?>"><?php echo e($val); ?>

                                        </label>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </ul>
                        </div> 
                    </div>
                </div>
                <div class="col-sm-6 col-md-2 srch_btn" style="display:none">
                    <div class="d-grid"> 
                        <button type="submit" class="btn add-btn"><i class="fa fa-arrow"></i>Search</button> 
                    </div>  
                </div>
            </div>
        </form>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">  
                    <?php if(isset($search_sells_data) && count($search_sells_data) > 0): ?>
                        <?php $__currentLoopData = $search_sells_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                            <div class="card target_sectiondiv">
                                <div class="card-body">
                                   
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="profile-view">
                                                    <div class="profile-basic">
                                                        <div class="row">
                                                            <h3><?php echo e($val->item_name); ?> Sale and tracking</h3>
                                                            <h4>Daily Sales</h4>
                                                            <form action="#" class="daily_sales_form_<?php echo e($val); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Sales</label>
                                                                        <input type="text" name="achieve_target" class="form-control allowfloatnumber" value="<?php echo e($sales_detail->target_price ?? ''); ?>">
                                                                        <span class="target_diff_pan"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Bill Count #C.A</label>
                                                                        <input type="text" name="bill_count" class="form-control allowfloatnumber" value="<?php echo e($sales_detail->target_price ?? ''); ?>">
                                                                        <span class="bill_count_span"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                    <label></label>
                                                                        <button type="button" class="btn btn-primary submit-btn save_store_btn">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <h4>Daily Tracking</h4>
                                                            <form action="#" class="heading_form_<?php echo e($val); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                <?php $heading_list = _tracking_heading_by_speriod($val->company_id,$val->branch_id,$val->id);?>
                                                                <?php if(isset($heading_list) && count($heading_list) > 0): ?>
                                                                    <?php $__currentLoopData = $heading_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hkey => $hval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo e(ucfirst($hval->title)); ?></label>
                                                                            <input type="text" name="heading_price[]" class="form-control allowfloatnumber">
                                                                        </div>
                                                                    </div>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label></label>
                                                                            <button type="button" class="btn btn-primary submit-btn save_store_btn">Save</button>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                   
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <!-- Add Selling Period Modal -->
    <div id="add_Form" class="modal custom-modal fade" role="dialog">
        <?php echo $__env->make('selling_management/selling_period_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <!-- /Add Selling Period Modal -->
    
    <!-- Delete Selling Period Modal -->
    <div class="modal custom-modal fade" id="delete_form" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Selling Period</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                              <form action="<?php echo e(route('selling_period.delete')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="selling_id" id="selling_delete_id">
                                    <button type="submit" class="btn btn-primary btn-large continue-btn" style="width: 100%;">Delete</button>
                              </form>
                                </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Selling Period Modal -->

</div>
<!-- /Page Wrapper -->


</div>


</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link href="<?php echo e(asset('assets/css/bootstrap-new.css')); ?>" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script type="text/javascript">
    $(document).on('click','.select_change',function(){
        
        var com_id = $('.sells_check:checked').val();
        if(com_id != ''){
            $('.srch_btn').show();
        }else{
            $('.srch_btn').hide();
        }
    });

</script>
<?php /**PATH C:\wamp64_new\www\hrm\resources\views/selling_management/store_daily_sales.blade.php ENDPATH**/ ?>