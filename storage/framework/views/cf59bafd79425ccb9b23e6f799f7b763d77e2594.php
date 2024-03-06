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
                    <h3 class="page-title">Waiter Daily Sales Target And Upselling</h3>
                </div>
            </div>
        </div>    
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="<?php echo e(route('userstore_daily_sales.list')); ?>" id="search_form">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col">
                    <h3 class="page-title"><?php echo e((isset($user->employee_branch) && !empty($user->employee_branch->name)) ? $user->employee_branch->name : ''); ?></h3>    
                </div>
                
                <div class="col-sm-6 col-md-2">  
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text" name="search_date" id="search_date" value="<?php echo (isset($search['search_date']) && !empty($search['search_date']))? $search['search_date']: date('d-m-Y'); ?>">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="user_id">
                            <option value="">Select User</option>
                            <?php foreach ($same_branch_users as $key => $val) {?>
                                <option value="<?php echo e($val->id); ?>" <?php echo (isset($search['user_id']) && $search['user_id']==$val->id)?'selected':''; ?>><?php echo e($val->first_name); ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">User</label>
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
                                        <?php $selected = ''; ?>
                                        <?php if(isset($search['sells_id']) && !empty($search['sells_id'])): ?>
                                            <?php
                                            if (in_array($key, $search['sells_id'])) { 
                                                $selected = 'checked';
                                            } else { 
                                                $selected = '';
                                            } 
                                            ?>
                                        <?php endif; ?>
                                        <li>
                                        <label>
                                            <input type="checkbox" class="sells_check select_change" name="sells_id[]" value="<?php echo e($key); ?>" <?php echo e($selected ?? ''); ?>><?php echo e($val); ?>

                                        </label>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </ul>
                        </div> 
                    </div>
                </div>
                <div class="col-sm-6 col-md-2 srch_btn">
                    <div class="d-grid"> 
                        <button type="submit" id="fwb" class="btn add-btn"><i class="fa fa-search"></i>Search</button> 
                    </div>  
                </div>
            </div>
        </form>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">Daily Target</h2>
                                <span><?php echo e(number_format($daily_target ?? 0,2)); ?> KWD</span>
                                <hr>
                                <b>MTD</b><span><?php echo e(number_format($mtd_target ?? 0,2)); ?> KWD</span>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">Daily Sales</h2>
                                <span><?php echo e(number_format($daily_sale ?? 0,2)); ?> KWD</span>
                                    <?php $calculate_per = _calculate_per($daily_target,$daily_sale); ?>
                                    <?php echo $calculate_per; ?>

                                <hr>
                                <b>MTD</b><span><?php echo e(number_format($mtd_sale ?? 0,2)); ?> KWD</span>
                                <?php $calculate_per = _calculate_per($mtd_target,$mtd_sale); ?>
                                    <?php echo $calculate_per; ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">Daily Score</h2>
                                <span><?php echo e(number_format($daily_score ?? 0,2)); ?></span>
                                <hr>
                                <b>MTD Average</b><span><?php echo e(number_format($mtd_score ?? 0,2)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">Complaint</h2>
                                <span><?php echo e($daily_cc ?? 0); ?></span>
                                <hr>
                                <b>MTD Total</b><span><?php echo e($mtd_cc ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-header sales_target">
            <div class="row align-items-center">
                <div class="col">  
                    <?php if(isset($search_sells_data) && count($search_sells_data) > 0): ?>
                        <?php $__currentLoopData = $search_sells_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                            <h4><?php echo e($val->item_name); ?> Sale and tracking</h4> 
                                <div class="card target_sectiondiv0">
                                    <div class="card-body">
                                    
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="profile-view">
                                                        <div class="profile-basic">
                                                            <div class="row">
                                                                
                                                                <h3>Daily Sales</h3>
                                                                <form action="<?php echo e(route('userstore_daily_sales.list')); ?>" method="post" class="daily_sales_form_<?php echo e($val->id); ?>">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php $is_daily_sales_exists = _is_upseldaily_sales_exists($val->company_id,$val->branch_id,$val->id,$search['user_id'],$search['search_date']); ?>
                                                                    <input type="hidden" name="sells_p_id" value="<?php echo e($val->id); ?>">
                                                                    <input type="hidden" name="daily_sales_id" value="<?php echo e($is_daily_sales_exists->id ?? ''); ?>">
                                                                    <input type="hidden" name="is_post" value="1">
                                                                    <input type="hidden" name="serch_date" value="<?php echo e($search['search_date'] ?? ''); ?>">
                                                                    <input type="hidden" name="user_id" value="<?php echo e($search['user_id'] ?? ''); ?>">
                                                                    <input type="hidden" name="s_id" value="<?php echo e(serialize($search['sells_id']) ?? ''); ?>">
                                                                    
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Target</label>
                                                                            <input type="text" name="achieve_target" class="form-control allowfloatnumber achieve_tar" data-id="<?php echo e($val->id); ?>" value="<?php echo e($is_daily_sales_exists->target_price ?? ''); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Sales</label>
                                                                            <input type="text" name="sales_val" class="form-control allowfloatnumber sales_val" data-id="<?php echo e($val->id); ?>" value="<?php echo e($is_daily_sales_exists->sale_price ?? ''); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <?php if($val->is_bill_count == '1'): ?>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Bill Count #C.A</label>
                                                                            <input type="text" name="bill_count" class="form-control allowfloatnumber bill_count_div" data-id="<?php echo e($val->id); ?>" value="<?php echo e($is_daily_sales_exists->bill_count ?? ''); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    <?php if($val->is_cc == '1'): ?>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>CC #C.A</label>
                                                                            <input type="text" name="cc_count" class="form-control allowfloatnumber bill_count_div" data-id="<?php echo e($val->id); ?>" value="<?php echo e($is_daily_sales_exists->cc_count ?? ''); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>    
                                                                    <h3>Upselling Score</h3>
                                                                    <div class="row">
                                                                    <?php $heading_list = _upselling_heading_by_speriod($val->company_id,$val->branch_id,$val->id);?>
                                                                    <?php if(isset($heading_list) && count($heading_list) > 0): ?>
                                                                        <?php $__currentLoopData = $heading_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hkey => $hval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php $selected = ''; ?>
                                                                        <?php if(isset($is_daily_sales_exists->headings) && !empty($is_daily_sales_exists->headings)): ?>
                                                                            <?php
                                                                            $headings = json_decode($is_daily_sales_exists->headings);
                                                                            ?>
                                                                            <?php if(isset($headings) && count($headings) > 0): ?>
                                                                                <?php $__currentLoopData = $headings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ehkey => $ehval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <?php if($ehval->id == $hval->id): ?>
                                                                                        <input type="hidden" name="tracking_id" value="<?php echo e($hval->id); ?>">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label><?php echo e(ucfirst($hval->title)); ?> <?php echo e((!empty($hval->parent_id)) ? 'Achieved' : 'Target'); ?></label>
                                                                                                <input type="hidden" name="heading_price[<?php echo e($hkey); ?>][id]" value="<?php echo e($hval->id); ?>">
                                                                                                <input type="text" name="heading_price[<?php echo e($hkey); ?>][price]" class="form-control allowfloatnumber" value="<?php echo e($ehval->price); ?>">
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                        <input type="hidden" name="tracking_id" value="<?php echo e($hval->id); ?>">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label><?php echo e(ucfirst($hval->title)); ?> <?php echo e((!empty($hval->parent_id)) ? 'Achieved' : 'Target'); ?></label>
                                                                                        <input type="hidden" name="heading_price[<?php echo e($hkey); ?>][id]" value="<?php echo e($hval->id); ?>">
                                                                                        <input type="text" name="heading_price[<?php echo e($hkey); ?>][price]" class="form-control allowfloatnumber">
                                                                                    </div>
                                                                                </div>
                                                                        <?php endif; ?>
                                                                      
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        
                                                                    <?php endif; ?>
                                                                  
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo e($is_daily_sales_exists->total_cal ?? 0); ?></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                        <label></label>
                                                                            <button type="submit" class="btn btn-primary submit-btn save_store_btn" data-id="<?php echo e($val->id); ?>">Save</button>
                                                                        </div>
                                                                    </div>
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

</div>
<!-- /Page Wrapper -->


</div>


</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link href="<?php echo e(asset('assets/css/bootstrap-new.css')); ?>" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.selectwith_search').select2({
            minimumResultsForSearch: 1,
            width: '100%'
        });
    });
    $('.bill_count_div').keyup(function(eve){
        var bill_val = $(this).val();
        var id = $(this).attr('data-id');
        var target_val = parseFloat($('.achieve_target_'+id).val());
        if(target_val != '' && bill_val != ''){
            var avg_val = target_val/bill_val;
            $(this).parent().find('.bill_count_avg_div').val(avg_val.toFixed(2));
            $(this).parent().find('.bill_count_span').text('Avg Bill '+avg_val.toFixed(2));
        }
    });


    $('.achieve_tar').keyup(function(eve){
        var id= $(this).attr('data-id');
         $('.achieve_target_'+id).val($(this).val());
    });


    $(".allowfloatnumber").keypress(function (eve) {
        if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
            eve.preventDefault();
        }

        // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
        $('.allowfloatnumber').keyup(function(eve) {
                if ($(this).val().indexOf('.') == 0) {
                $(this).val($(this).val().substring(1));
                }
            });
    });

</script>
<?php /**PATH C:\wamp64_new\www\hrm\resources\views/up_selling_management/user_daily_sales.blade.php ENDPATH**/ ?>