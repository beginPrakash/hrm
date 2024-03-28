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
                                <h3 class="page-title">User Roles</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">User Roles</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add User Roles</a>
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
                                            <th>Employee Name</th>
                                            <th>Role</th>
                                            <th>Company</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($uroles_data))
                                        {
                                            $i = 0;
                                            foreach($uroles_data as $data)
                                            {
                                                $i++;
                                            $company_name = _get_company_name_by_uroles($data->id,'name'); 
                                            if((isset($data->employee) && $data->employee->profile != Null))
                                                {
                                                    $image = 'uploads/profile/'.$data->employee->profile;
                                                }
                                                else{
                                                    $image = 'assets/img/profiles/avatar.png';
                                                }   
                                            ?>
                                           
                                                <tr>
                                                    <td><img alt="" src="<?php echo $image; ?>"></td>
                                                    <td><a href="<?php echo e('/employeeProfileUpdate?id='.$data->employee_id); ?>"><?php echo e((isset($data->employee) && !empty($data->employee)) ? $data->employee->full_name : ''); ?></a></td>
                                                    <td><?php echo e((isset($data->roles_detail) && !empty($data->roles_detail)) ? $data->roles_detail->title : ''); ?></td>
                                                    <td>
                                                        <?php if(isset($company_name) && count($company_name) > 0): ?>
                                                            <?php $__currentLoopData = $company_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <ul>
                                                                <li><?php echo e($val->name); ?></li>
                                                            </ul>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#add_leave" data-id="<?php echo e($data->id); ?>" class="action-icon edit_hierarchy"><i class="fa fa-pencil"></i></a>
                                                        <a href="#" data-toggle="modal" data-target="#delete_user_roles" data-id="<?php echo e($data->id); ?>" class="delete_user_roles"><i class="fa fa-trash-o m-r-5" ></i></a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Page Content -->
                
                <!-- Add Role Modal -->
                <div id="add_leave" class="modal custom-modal fade modal_div" role="dialog">
                    <?php echo $__env->make('roles_permission/user_roles_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <!-- /Add Role Modal -->

                <!-- Delete Role Modal -->
                <div class="modal custom-modal fade" id="delete_user_roles" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Delete User Roles</h3>
                                    <p>Are you sure want to delete?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <div class="row">
                                        <div class="col-6">
                                            <form action="<?php echo e(route('user_roles.delete')); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="roles_id" id="roles_id" value="">
                                                <button type="submit" class="btn btn-primary btn-large continue-btn" style="width: 100%;">Delete</button>
                                            </form>
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
                <!-- /Delete Role Modal -->
   
            </div>
            <!-- /Page Wrapper -->


<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link href="<?php echo e(asset('assets/css/bootstrap-new.css')); ?>" rel="stylesheet"/>


<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#addEditForm").validate({
            rules: {
                role_id: {
                    required : true
                },
                emp_id: {
                    required : true
                },
                'company[]': {
                    required : true
                },
                'brnach_list[]': {
                    required : true
                },
            },    
            messages: {
                role_id: {
                    required : 'Role is required',
                },
                emp_id: {
                    required : 'Employee is required',
                },
                'company[]': {
                    required : 'Company is required',
                },
                'brnach_list[]': {
                    required : 'Branch is required',
                },
            },
            errorPlacement: function (error, element) {
                if (element.prop("type") == "text" || element.prop("type") == "number" || element.prop("type") == "textarea") {
                    error.insertAfter(element);
                } else {
                    error.insertAfter(element.parent());
                }
            },
        });
    });

    $(document).on('click','.edit_hierarchy',function(){
        $('#add_leave').html('');
        var id= $(this).attr('data-id');
        $.ajax({
        url: "<?php echo e(route('user_roles_details')); ?>",
        type: "POST",
        dataType: "json",
        data: {"_token": "<?php echo e(csrf_token()); ?>", id:id},
        success:function(response)
            {
                $('#add_leave').html(response.html).fadeIn();
            }
        });
    });

    $(document).on('click','.delete_user_roles',function(){
        var id= $(this).attr('data-id');
        $('#roles_id').val(id);
    });

    $('#add_leave').on('hidden.bs.modal', function () {
        $('#title').val('');
        $('.role_id').val('');
        $('.leave_m_title').text('Add Roles');
    });
       
    $(document).on('click','.company_check',function(){
        $('.branch_menu').remove();
        var sel_val = $('.company_check:checked').map(function() {
            return this.value;
        }).get().join(',');

        $.ajax({
            url: "<?php echo e(route('sales_target.branchlistbycompany')); ?>",
            type: "POST",
            dataType: "json",
            data: {"_token": "<?php echo e(csrf_token()); ?>", sel_val:sel_val},
            success:function(response)
                {
                    $('.branch_checklist').after(response.html).fadeIn();
                }
        });
    });

    $('.selectwith_search').select2({
		minimumResultsForSearch: 1,
		width: '100%'
	});
    
</script>
<?php /**PATH C:\wamp64_new\www\hrm\resources\views/roles_permission/user_roles.blade.php ENDPATH**/ ?>