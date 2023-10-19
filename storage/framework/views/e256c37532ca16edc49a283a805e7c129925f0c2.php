<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Leave Settings</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leave Settings</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        
        <div class="row">
            <div class="col-md-12">
            	<?php 
            	if(isset($leaveSettings))
            	{
            		foreach($leaveSettings as $ls)
            		{
            		?>
		                <div class="card leave-box" id="leave_settings<?php echo $ls->id; ?>">
		                    <div class="card-body">
		                    	<div class="h3 card-title with-switch">
		                            <?php echo $ls->name; ?>  

		                            

		                            <div class="onoffswitch">
		                                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox switch_annual" id="switch_annual" <?php echo ($ls->status=='active')?'checked':''; ?> value="<?php echo $ls->id; ?>" data-data="<?php echo $ls->id; ?>">
		                                <label class="onoffswitch-label" for="switch_annual">
		                                    <span class="onoffswitch-inner"></span>
		                                    <span class="onoffswitch-switch"></span>
		                                </label>
		                            </div>
		                        </div>

	                            <div class="alert alert-success alert-dismissible fade show d-none" role="alert" id="success_message<?php echo $ls->id; ?>">
									  <strong>Updated Successfully!</strong>
								</div>

		                        <div class="leave-item">
		                        	
		                            <!-- Annual Days Leave -->
		                            <div class="leave-row">
		                                <div class="leave-left">
		                                    <div class="input-box">
		                                        <div class="form-group">
		                                            <label>Days/Month</label>
		                                            <input type="text" class="form-control" name="leave_days<?php echo $ls->id; ?>" id="leave_days<?php echo $ls->id; ?>" disabled value="<?php echo $ls->days; ?>"> 
		                                        </div>
		                                    </div>
		                                </div>
                                        <?php if($ls->id==1){
                                        $tot = (isset($ls->days))?$ls->days * 12:0; ?>
                                            <div class="leave-middle ml-3" style="margin-left: 30px;">
                                                <div class="input-box">
                                                    <div class="form-group">
                                                        <label>Total Days/ Year</label>
                                                        <input type="text" class="form-control" name="leave_days_year<?php echo $ls->id; ?>" id="leave_days_year<?php echo $ls->id; ?>" readonly value="<?php echo $tot; ?>"> 
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
		                                <div class="leave-right">
		                                    <button class="leave-edit-btn" data-id="<?php echo $ls->id; ?>" data-data="1">Edit</button>
		                                </div>
		                            </div>
		                            <!-- /Annual Days Leave -->
		                            
		                            <?php if($ls->carry_forward_required == 'yes') { ?>
		                            <!-- Carry Forward -->
		                            <div class="leave-row">
		                                <div class="leave-left">
		                                    <div class="input-box">
		                                        <label class="d-block">Carry forward</label>
		                                        <div class="leave-inline-form">
		                                            <div class="form-check form-check-inline">
		                                                <input class="form-check-input" type="radio" name="carry_fwd<?php echo $ls->id; ?>" id="carry_no" value="1" <?php echo ($ls->carry_forward==1)?'checked':''; ?> disabled>
		                                                <label class="form-check-label" for="carry_no">No</label>
		                                            </div>
		                                            <div class="form-check form-check-inline">
		                                                <input class="form-check-input" type="radio" name="carry_fwd<?php echo $ls->id; ?>" id="carry_yes" value="2" <?php echo ($ls->carry_forward==2)?'checked':''; ?> disabled>
		                                                <label class="form-check-label" for="carry_yes">Yes</label>
		                                            </div>
		                                            <!-- <div class="input-group">
		                                                <span class="input-group-text">Max</span>
		                                                <input type="text" class="form-control" disabled name="max_leaves<?php echo $ls->id; ?>" id="max_leaves<?php echo $ls->id; ?>" value="<?php echo $ls->carry_forward_max; ?>">
		                                            </div> -->
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class="leave-right">
		                                    <button class="leave-edit-btn" data-id="<?php echo $ls->id; ?>" data-data="2">
		                                        Edit
		                                    </button>
		                                </div>
		                            </div>
		                            <!-- /Carry Forward -->
		                        	<?php } ?>
		                            
		                            <?php if($ls->earned_leave_required == 'yes') { ?>
		                            <!-- Earned Leave -->
		                            <!-- <div class="leave-row">
		                                <div class="leave-left">
		                                    <div class="input-box">
		                                        <label class="d-block">Earned leave</label>
		                                        <div class="leave-inline-form">
		                                            <div class="form-check form-check-inline">
		                                                <input class="form-check-input" type="radio" name="earned_leaves<?php echo $ls->id; ?>" id="earned_no" value="1" disabled <?php echo ($ls->earned_leave==1)?'checked':''; ?>>
		                                                <label class="form-check-label" for="earned_no">No</label>
		                                            </div>
		                                            <div class="form-check form-check-inline">
		                                                <input class="form-check-input" type="radio" name="earned_leaves<?php echo $ls->id; ?>" id="earned_yes" value="2" disabled <?php echo ($ls->earned_leave==2)?'checked':''; ?>>
		                                                <label class="form-check-label" for="earned_yes">Yes</label>
		                                            </div>
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class="leave-right">
		                                    <button class="leave-edit-btn" id="leave-edit-btn<?php echo $ls->id; ?>" data-id="<?php echo $ls->id; ?>" data-data="3">
		                                        Edit
		                                    </button>
		                                </div>
		                            </div> -->
		                            <!-- /Earned Leave -->
		                            <?php } ?>

                                    <?php if($ls->extra_rules !== '') { ?>
                                        <div class="leave-row">
                                            <div class="leave-left">
                                                <ul type="bullet">
                                                    <?php
                                                    $ex_er = explode(',', $ls->extra_rules);
                                                    foreach($ex_er as $er)
                                                    {
                                                        ?>
                                                        <li><?php echo $er; ?></li>
                                                    <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php } ?>

		                            <input type="hidden" name="token<?php echo $ls->id; ?>" id="token<?php echo $ls->id; ?>" value="<?php echo e(csrf_token()); ?>">
		                            <input type="hidden" class="leave_id" name="leave_id[<?php echo $ls->id; ?>]" value="<?php echo $ls->id; ?>" data-id="<?php echo $ls->id; ?>">

		                        </div>
		                        
		                    </div>
		                </div>
            		<?php
            		}
            	}
            	?>
                
            </div>
        </div>
            
    </div>
    <!-- /Page Content -->
    
    <!-- Add Custom Policy Modal -->
    <div id="add_custom_policy" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Custom Policy</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>Policy Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Days <span class="text-danger">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group leave-duallist">
                            <label>Add employee</label>
                            <div class="row">
                                <div class="col-lg-5 col-sm-5">
                                    <select name="customleave_from" id="customleave_select" class="form-control form-select" size="5" multiple="multiple">
                                        <option value="1">Bernardo Galaviz </option>
                                        <option value="2">Jeffrey Warden</option>
                                        <option value="2">John Doe</option>
                                        <option value="2">John Smith</option>
                                        <option value="3">Mike Litorus</option>
                                    </select>
                                </div>
                                <div class="multiselect-controls col-lg-2 col-sm-2 d-grid gap-2">
                                    <button type="button" id="customleave_select_rightAll" class="btn w-100 btn-white"><i class="fa fa-forward"></i></button>
                                    <button type="button" id="customleave_select_rightSelected" class="btn w-100 btn-white"><i class="fa fa-chevron-right"></i></button>
                                    <button type="button" id="customleave_select_leftSelected" class="btn w-100 btn-white"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" id="customleave_select_leftAll" class="btn w-100 btn-white"><i class="fa fa-backward"></i></button>
                                </div>
                                <div class="col-lg-5 col-sm-5">
                                    <select name="customleave_to" id="customleave_select_to" class="form-control form-select" size="8" multiple="multiple"></select>
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
    <!-- /Add Custom Policy Modal -->
    
    <!-- Edit Custom Policy Modal -->
    <div id="edit_custom_policy" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Custom Policy</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>Policy Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="LOP">
                        </div>
                        <div class="form-group">
                            <label>Days <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="4">
                        </div>
                        <div class="form-group leave-duallist">
                            <label>Add employee</label>
                            <div class="row">
                                <div class="col-lg-5 col-sm-5">
                                    <select name="edit_customleave_from" id="edit_customleave_select" class="form-control form-select" size="5" multiple="multiple">
                                        <option value="1">Bernardo Galaviz </option>
                                        <option value="2">Jeffrey Warden</option>
                                        <option value="2">John Doe</option>
                                        <option value="2">John Smith</option>
                                        <option value="3">Mike Litorus</option>
                                    </select>
                                </div>
                                <div class="multiselect-controls col-lg-2 col-sm-2 d-grid gap-2">
                                    <button type="button" id="edit_customleave_select_rightAll" class="btn w-100 btn-white"><i class="fa fa-forward"></i></button>
                                    <button type="button" id="edit_customleave_select_rightSelected" class="btn w-100 btn-white"><i class="fa fa-chevron-right"></i></button>
                                    <button type="button" id="edit_customleave_select_leftSelected" class="btn w-100 btn-white"><i class="fa fa-chevron-left"></i></button>
                                    <button type="button" id="edit_customleave_select_leftAll" class="btn w-100 btn-white"><i class="fa fa-backward"></i></button>
                                </div>
                                <div class="col-lg-5 col-sm-5">
                                    <select name="customleave_to" id="edit_customleave_select_to" class="form-control form-select" size="8" multiple="multiple"></select>
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
    <!-- /Edit Custom Policy Modal -->
    
    <!-- Delete Custom Policy Modal -->
    <div class="modal custom-modal fade" id="delete_custom_policy" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Custom Policy</h3>
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
    <!-- /Delete Custom Policy Modal -->
    
</div>
<!-- /Page Wrapper -->

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
	function changeStatus(val)
	{
		var checkboxValues = $('.myCheckbox:checked').map(function() {
    console.log($(this).val());
}).get();
	}
</script>

<script type="text/javascript">
$(document).on('keyup', '#leave_days1', function()
{
	var d = $('#leave_days1').val();
    $('#leave_days_year1').val( d*12);
});
</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/policies/leave.blade.php ENDPATH**/ ?>