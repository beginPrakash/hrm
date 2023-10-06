<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
        <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
        <!-- Page Header -->
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Overtime</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Overtime</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="/overtimeUpdate" method="post" id="addEditForm">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label>Working days/Month <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" value="<?php echo $overtime->working_days; ?>" id="working_days" name="working_days" required>
                            </div>
                            <div class="form-group">
                                <label>Working hours/day <span class="text-danger">*</span></label>
                                <input class="form-control" value="<?php echo $overtime->working_hours; ?>" type="text" id="working_hours" name="working_hours" required>
                            </div>
                            <div class="form-group">
                                <label>Default OFF Day <span class="text-danger">*</span></label>
                                <select class="form-control" id="off_day" name="off_day" required>
                                    <?php 
                                    $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                                    foreach($days as $key => $day) { ?>
                                        <option value="<?php echo $key+1; ?>" <?php echo ($overtime->off_day==$key+1)?'selected':''; ?>><?php echo $day; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /Page Content -->

</div>
<!-- /Page Wrapper -->


<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eqb1fxfgkdl8/public_html/hrm/resources/views/policies/overtime.blade.php ENDPATH**/ ?>