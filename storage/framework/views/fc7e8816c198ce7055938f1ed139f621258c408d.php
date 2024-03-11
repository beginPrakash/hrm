<script type="text/javascript" src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
<script>
    $(document).on('click','.serc_btn',function(){
     
        //$('#search_modal').html('');
        var date_val = $('#searchsale_date').val();
        console.log(date_val);
        var sell_id_default = $('#sell_id_default').val();
        $.ajax({
            url: "<?php echo e(route('dashboard.search_sales')); ?>",
            type: "POST",
            dataType: "json",
            data: {"_token": "<?php echo e(csrf_token()); ?>", date_val:date_val,sell_id_default:sell_id_default},
            success:function(response)
                {
                    $('#search_modal').html('');
                    $('#search_modal').html(response.html).fadeIn();
                }
        });
    })
    </script>

<div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="sickleaveModalLabel">Sale & Target History</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4 search_div">
                    <div class="row">
                        <!-- Search Filter -->
                        <form method="post" action="<?php echo e(route('dashboard.search_sales')); ?>" id="search_form">
                            <?php echo csrf_field(); ?>
                            <div class="row">            
                                <div class="col-sm-6 col-md-6">  
                                    <div class="form-group form-focus focused">
                                        <div class="cal-icon">
                                            <input class="form-control floating datetimepicker" type="text" name="search_date" id="searchsale_date" value="<?php echo (isset($search['search_date']) && !empty($search['search_date']))? $search['search_date']: date('d-m-Y'); ?>">
                                        </div>
                                        <label class="focus-label">From</label>
                                    </div>
                                </div>
                                <input type="hidden" name="sell_id_default" id="sell_id_default" value="<?php echo e(serialize($sell_id_default ?? '')); ?>">
                                <div class="col-sm-6 col-md-2 srch_btn">
                                    <div class="d-grid"> 
                                        <button type="button" id="fwb" class="btn add-btn serc_btn"><i class="fa fa-search"></i>Search</button> 
                                    </div>  
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php if(isset($date_list) && count($date_list) > 0): ?>
                        <?php $__currentLoopData = $date_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <i class="fa fa-clock-o" aria-hidden="true"></i><span><?php echo e((isset($val) && !empty($val)) ? date('d, M', strtotime($val)) : ''); ?></span>
                        <div class="vertical">
                            <?php $find_sales_detail = _find_upsales_detail_by_date($company_id,$branch_id,$uid,$sell_id_default,$val); ?>
                            <div class="row">
                                    <div class="col-sm-3">
                                        <div class="card dash-widget mb-0">
                                            <div class="py-4">
                                                <div class="dash-widget-info text-center">
                                                    <h2 class="sales_title">Target</h2>
                                                    <?php if(!empty($find_sales_detail)): ?>
                                                    <span><?php echo e(number_format($find_sales_detail->target_price ?? 0,2)); ?> KWD</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="card dash-widget mb-0">
                                            <div class="py-4">
                                                <div class="dash-widget-info text-center">
                                                    <h2 class="sales_title">Sale</h2>
                                                    <?php if(!empty($find_sales_detail)): ?>
                                                    <span><?php echo e(number_format($find_sales_detail->sale_price ?? 0,2)); ?> KWD</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="card dash-widget mb-0">
                                            <div class="py-4">
                                                <div class="dash-widget-info text-center">
                                                    <h2 class="sales_title">Variance</h2>
                                                    <?php if(!empty($find_sales_detail)): ?>
                                                    <?php  $today_vari = $find_sales_detail->sale_price - $find_sales_detail->target_price ;?>
                                                    <span><?php echo e(number_format($today_vari ?? 0,2)); ?> KWD</span>
                                                    <?php endif; ?>
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
        </div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/up_selling_management/search_modal.blade.php ENDPATH**/ ?>