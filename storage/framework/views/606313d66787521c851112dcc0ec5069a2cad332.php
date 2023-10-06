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
                    <h3 class="page-title"><?php echo $title; ?> Settings</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active"><?php echo $title; ?> Settings</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        
        <div class="row">
            <div class="col-md-12">
                <form action="/updateIndemnity" method="post">
                    <?php echo csrf_field(); ?>
                	<?php 
                	if(isset($indemnitySettings))
                	{
                		foreach($indemnitySettings as $is)
                		{
                		?>
    		                <div class="card leave-box" id="leave_settings<?php echo $is->id; ?>">
    		                    <div class="card-body">
    		                    	<div class="h3 card-title with-switch">
    		                            <?php echo $is->min_year; ?>  <?php echo ($is->max_year==0)?'+':'to '.$is->max_year; ?> Years

    		                            <!-- <div class="onoffswitch">
    		                                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox switch_annual" id="switch_annual" <?php echo ($is->status=='active')?'checked':''; ?> value="<?php echo $is->id; ?>" data-data="<?php echo $is->id; ?>">
    		                                <label class="onoffswitch-label" for="switch_annual">
    		                                    <span class="onoffswitch-inner"></span>
    		                                    <span class="onoffswitch-switch"></span>
    		                                </label>
    		                            </div> -->
    		                        </div>

    	                            <div class="alert alert-success alert-dismissible fade show d-none" role="alert" id="success_message<?php echo $is->id; ?>">
    									  <strong>Updated Successfully!</strong>
    								</div>

    		                        <div class="leave-item">
    		                        	
    		                            <div class="row">
                                           <!--  <div class="leave-left col-6">
                                                <div class="input-box">
                                                    <div class="form-group">
                                                        <label>Min Year</label>
                                                        <input type="text" class="form-control" name="min_year[<?php echo $is->id; ?>]" id="min_year<?php echo $is->id; ?>" value="<?php echo $is->min_year; ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="leave-left col-6">
                                                <div class="input-box">
                                                    <div class="form-group">
                                                        <label>Max Year</label>
                                                        <input type="text" class="form-control" name="max_year[<?php echo $is->id; ?>]" id="max_year<?php echo $is->id; ?>" value="<?php echo $is->max_year; ?>" required>
                                                    </div>
                                                </div>
                                            </div> -->
    		                                <div class="leave-left col-6">
    		                                    <div class="input-box">
    		                                        <div class="form-group">
    		                                            <label>Indemnity Amount</label>
    		                                            <input type="text" class="form-control" name="indemnity_amount[<?php echo $is->id; ?>]" id="indemnity_amount<?php echo $is->id; ?>" value="<?php echo $is->indemnity_amount; ?>" required>
    		                                        </div>
    		                                    </div>
    		                                </div>
    		                                <!-- <div class="leave-right">
    		                                    <button class="leave-edit-btn" data-id="<?php echo $is->id; ?>" data-data="1">Edit</button>
    		                                </div> -->
    		                            <!-- </div>
                                        <div class="leave-row"> -->
                                            <div class="leave-left col-6">
                                                <div class="input-box">
                                                    <div class="form-group">
                                                        <label>Payable % of Amount</label>
                                                        <input type="text" class="form-control" name="percentage_ia[<?php echo $is->id; ?>]" id="percentage_ia<?php echo $is->id; ?>" value="<?php echo $is->percentage_ia; ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="leave-right">
                                                <button class="leave-edit-btn" data-id="<?php echo $is->id; ?>" data-data="1">Edit</button>
                                            </div> -->
                                        </div>

    		                        </div>
    		                        
    		                    </div>
    		                </div>
                		<?php
                		}
                	}
                	?>

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-md btn-info text-white">Update</button>
                        </div>
                    </div>
                </form>
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
	// $(document).on('click', '.leave-save-btn', function()
	// {
	// 	alert($(this).find('.leave_id').attr('id'));
	// });
</script><?php /**PATH C:\xampp81\htdocs\hrmumair\resources\views/policies/indemnity.blade.php ENDPATH**/ ?>