<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Page Wrapper -->
<div class="page-wrapper">
    
    <!-- Page Content -->
    <div class="content container-fluid">
        <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Transportation Detail</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Transportation Detail</li>
                    </ul>
                </div>
            </div>
        </div>      

        <!-- /Page Header -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-basic">
                                <div class="row">
                                    
                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <?php if(isset($trans_detail->car_name) && !empty($trans_detail->car_name)): ?>
                                                <li>
                                                    <div class="title">Car Name:</div>
                                                    <div class="text"><?php echo e($trans_detail->car_name ?? "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->colour) && !empty($trans_detail->colour)): ?>
                                            <li>
                                                <div class="title">Colour:</div>
                                                <div class="text"><a href="#"><?php echo e($trans_detail->colour ?? "--"); ?></a></div>
                                            </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->model) && !empty($trans_detail->model)): ?>
                                                <li>
                                                    <div class="title">Model:</div>
                                                    <div class="text"><?php echo e($trans_detail->model ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->license_no) && !empty($trans_detail->license_no)): ?>
                                                <li>
                                                    <div class="title">License Number:</div>
                                                    <div class="text"><?php echo e($trans_detail->license_no ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->license_expiry) && !empty($trans_detail->license_expiry)): ?>
                                                <li>
                                                    <div class="title">License Expiry:</div>
                                                    <div class="text"><?php echo e($trans_detail->license_expiry ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->alert_days) && !empty($trans_detail->alert_days)): ?>
                                                <li>
                                                    <div class="title">Alert Days:</div>
                                                    <div class="text"><?php echo e($trans_detail->alert_days ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->remarks) && !empty($trans_detail->remarks)): ?>
                                                <li>
                                                    <div class="title">Remarks:</div>
                                                    <div class="text"><?php echo e($trans_detail->remarks ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->driver) && !empty($trans_detail->driver)): ?>
                                                <li>
                                                    <div class="title">Driver:</div>
                                                    <div class="text"><?php echo e($trans_detail->driver ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->tag) && !empty($trans_detail->tag)): ?>
                                                <li>
                                                    <div class="title">Tag:</div>
                                                    <div class="text"><?php echo e($trans_detail->tag ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->under_company) && !empty($trans_detail->under_company)): ?>
                                                <li>
                                                    <div class="title">Company:</div>
                                                    <div class="text"><?php echo e((isset($trans_detail->com_detail) && !empty($trans_detail->com_detail)) ? $trans_detail->com_detail->name :  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->under_subcompany) && !empty($trans_detail->under_subcompany)): ?>
                                                <li>
                                                    <div class="title">Subcompany:</div>
                                                    <div class="text"><?php echo e((isset($trans_detail->subcom_detail) && !empty($trans_detail->subcom_detail)) ? $trans_detail->subcom_detail->name :  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($trans_detail->cost) && !empty($trans_detail->cost)): ?>
                                                <li>
                                                    <div class="title">Cost:</div>
                                                    <div class="text"><?php echo e($trans_detail->cost ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="pro-edit"><a data-bs-toggle="modal" data-bs-target="#add_transp" class="edit-icon edit_trans_btn" data-id="<?php echo e($trans_detail->id); ?>" ><i class="fa fa-pencil"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        <div id="add_transp" class="modal custom-modal fade" role="dialog">
            <?php echo $__env->make('transportation/transportation_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

    </div>
    <!-- /Page Content -->

    
</div>
<!-- /Page Wrapper -->

</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
   $(document).on('click','.edit_trans_btn',function(){
        $('#add_transp').html('');
        var id= $(this).attr('data-id');
        $.ajax({
        url: "<?php echo e(route('gettranspoDetailsById')); ?>",
        type: "POST",
        dataType: "json",
        data: {"_token": "<?php echo e(csrf_token()); ?>", id:id},
        success:function(response)
            {
                $('#add_transp').html(response.html).fadeIn();
            }
        });
    });

    $(document).on('click','.deleteDocButton',function(){
        var id = $(this).data('data');
        $.ajax({
        url: "<?php echo e(route('delete_transp_document')); ?>",
        type: "POST",
        dataType: "json",
        data: {"_token": "<?php echo e(csrf_token()); ?>", id:id},
        success:function(response)
            {
                $('.doc_'+response.id).remove();
            }
        });
    });
</script>
<?php /**PATH C:\wamp64_new\www\hrm\resources\views/transportation/detail.blade.php ENDPATH**/ ?>