<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


 <!-- Page Wrapper -->
            <div class="page-wrapper">

                <!-- Page Content -->
                <div class="content container-fluid">
                    
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Leaves</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Leave Request</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class=" table table-bordered table-striped table-hover datatable datatable-LoanApplication">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;">#</th>
                                            <th>Applied By</th>
                                            <th>Leave Type</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>No of Days</th>
                                            <th>Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php if(isset($leave_approvaldata) && count($leave_approvaldata) > 0): ?>
                                            <?php $__currentLoopData = $leave_approvaldata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo e((isset($val->leave_user) && !empty($val->leave_user)) ? $val->leave_user->full_name : ''); ?></td>
                                                    <td><?php echo e((isset($val->leaves_details) && !empty($val->leaves_details)) ? $val->leaves_details->leaves_leavetype->name : ''); ?></td>
                                                    <td><?php echo e((isset($val->leaves_details) && !empty($val->leaves_details)) ? date('d-m-Y', strtotime($val->leaves_details->leave_from)) : ''); ?></td>
                                                    <td><?php echo e((isset($val->leaves_details) && !empty($val->leaves_details)) ? date('d-m-Y', strtotime($val->leaves_details->leave_to)) : ''); ?></td>
                                                    <td><?php echo e((isset($val->leaves_details) && !empty($val->leaves_details)) ? $val->leaves_details->leave_days : ''); ?></td>
                                                    <td><?php echo e((isset($val->leaves_details) && !empty($val->leaves_details)) ? $val->leaves_details->leave_reason : ''); ?></td>
                                                    <td>
                                                        <div class="dropdown action-label">
                                                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <?php echo e((isset($val->leaves_details) && !empty($val->leaves_details)) ? $val->leaves_details->leave_status : ''); ?>

                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item approveButton" href="#" data-bs-toggle="modal" data-bs-target="#approve_leave" data-id="<?php echo e($val->leave_id); ?>" data-data="<?php echo $val->leave_id; ?>"><i class="fa fa-dot-circle-o text-success"></i> Approve</a>
                                                                <a class="dropdown-item rejectButton" href="#" data-bs-toggle="modal" data-bs-target="#reject_leave" data-id="<?php echo e($val->leave_id); ?>" data-data="<?php echo $val->leave_id; ?>"><i class="fa fa-dot-circle-o text-danger"></i> Reject</a>
                                                            </div>
                                                            
                                                        </div>
                                                    </td>
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
                <!-- /Page Content -->

                <!-- Approve Leave Modal -->
                <div class="modal custom-modal fade" id="approve_leave" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Leave Approve</h3>
                                    <p>Are you sure want to approve for this leave?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <form action="/leaveApprove" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="1">
                                        <input type="hidden" name="approval_by" id="approval_by" value="">
                                        <input type="hidden" name="leave_id" id="leave_id" value="">
                                        <div class="row">
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary continue-btn" style="width: 100%;">Approve</button>
                                            </div>
                                            <div class="col-6">
                                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Close</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Approve Leave Modal -->
                
                <!-- Delete Leave Modal -->
                <div class="modal custom-modal fade" id="reject_leave" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Reject Leave</h3>
                                    <p>Are you sure want to reject this leave?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <form action="/leaveReject" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="2">
                                        <input type="hidden" name="reject_by" id="reject_by" value="">
                                        <input type="hidden" name="leave_id" id="reject_leave_id" value="">
                                        <div class="row">
                                            <div class="col-12">
                                                <textarea class="form-control" name="reject_reason" required placeholder="Reason for Rejection"></textarea>
                                            </div>
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary continue-btn" style="width: 100%;">Reject</button>
                                            </div>
                                            <div class="col-6">
                                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Close</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Delete Leave Modal -->

                <!-- Cancel Leave Modal -->
                <div class="modal custom-modal fade" id="cancel_leave" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Cancel Leave</h3>
                                    <p>Are you sure want to cancel this leave?</p>
                                </div>
                                <div class="modal-btn cancel-action">
                                    <form action="/leaveCancel" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="2">
                                        <input type="hidden" name="leave_id" id="cancel_leave_id" value="">
                                        <div class="row">
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary continue-btn" style="width: 100%;">Cancel</button>
                                            </div>
                                            <div class="col-6">
                                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Close</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Cancel Leave Modal -->
                
            </div>
            <!-- /Page Wrapper -->


<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    $(document).on('click','.approveButton',function(){
        var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#leave_id').val(lid);
        $('#approval_by').val(id);
    })

    $(document).on('click','.rejectButton',function(){
        var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#reject_leave_id').val(lid);
        $('#reject_by').val(id);
    })
</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/lts/leave_request.blade.php ENDPATH**/ ?>