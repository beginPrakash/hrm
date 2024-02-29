<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- Page Wrapper -->
<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
    <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
        <!-- Page Header -->
        <?php echo $__env->make('includes/breadcrumbs', ['title' => $title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>            
        <!-- /Page Header -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <table class="table table-striped custom-table mb-0 datatablex" id="dt_table">
                        <thead>
                        

                            <tr>
                                <th style="width: 30px;">#</th>
                                <th>Job title </th>
                                <th>User Type </th>
                                <!-- <th>Department </th> -->
                                <th class="text-end">Action</th>
                            </tr>
                            
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <!-- Add Job title Modal -->
    <div id="add_Form" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Job title</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/designationInsert" method="post" id="addForm">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>Job title Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="designation" id="designation">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Multi User <i class="fa fa-info-circle" title="Can add multiple users to this designation."></i></label>
                                    <br>
                                    <input type="checkbox" name="multi_user" value="1" checked>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <br>
                                    <input type="checkbox" name="is_sales" value="1">
                                    <label>Sales</label>   
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label>Department <span class="text-danger">*</span></label>
                            <select class="form-control select" name="department" id="department"> -->
                                <!-- <option value="">Select Department</option> -->
                                <?php
                                // if(isset($allDepartments))
                                // {
                                //     foreach ($allDepartments as $drow) 
                                //     {
                                        ?>
                                        <!-- <option value="<?php //echo $drow->id; ?>"><?php //echo $drow->name; ?></option> -->
                                        <?php 
                                //     }
                                // }
                                ?>
                            <!-- </select>
                        </div> -->
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Job title Modal -->
    
    <!-- Edit Job title Modal -->
    <div id="edit_designation" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Job title</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="/designationUpdate" method="post" id="editForm">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>Job title Name <span class="text-danger">*</span></label>
                                        <input class="form-control" value="" id="designations_name" name="designations_name" type="text">
                                        <input class="form-control" value="" id="designations_id" name="designations_id" type="hidden">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Multi User <i class="fa fa-info-circle" title="Can add multiple users to this designation."></i></label>
                                        <br>
                                        <input type="checkbox" name="multi_user" id="multi_user" value="1" checked>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <br>
                                        <input type="checkbox" name="is_sales" id="is_sales" value="1">
                                        <label>Sales</label>   
                                    </div>
                                </div>
                            </div>
                        <!-- <div class="form-group">
                            <label>Department <span class="text-danger">*</span></label>
                            <select class="form-control select" id="edit_department" name="department"> -->
                                <!-- <option value="">Select Department</option> -->
                                <?php
                                // if(isset($allDepartments))
                                // {
                                //     foreach ($allDepartments as $derow) 
                                //     {
                                        ?>
                                        <!-- <option value="<?php //echo $derow->id; ?>"><?php //echo $derow->name; ?></option> -->
                                        <?php 
                                //     }
                                // }
                                ?>
                            <!-- </select>
                            <input class="form-control" value="" id="selected_department_id" name="selected_department_id" type="hidden" value="">
                        </div> -->
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Designation Modal -->
    
    <!-- Delete Designation Modal -->
    <div class="modal custom-modal fade" id="delete_designation" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Designation</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                            <form action="/designationDelete" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="designations_id" id="designations_delete_id">
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
    <!-- /Delete Designation Modal -->

</div>
<!-- /Page Wrapper -->


</div>


</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        var i = 1;
        var table_table = $('#dt_table').DataTable({
            responsive: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(route('designation')); ?>",
            },

            columns: [
                {
                    "render": function() {
                        return i++;
                    }
                },
                {
                    data: 'designation',
                    name: 'designation',

                },
                {
                    data: 'multi_user',
                    name: 'multi_user',

                },
                // {
                //     data: 'department',
                //     name: 'department',

                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
        });
    });
</script>

<script>
    $(document).on('click','.editButton', function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $('#multi_user').removeAttr('checked');
        $.each(JSON.parse(decodedData), function(key,value){
            $('#designations_'+key).val(value);
            if(key=='multi_user')
            {
                if(value==1)
                {
                    $('#multi_user').prop('checked', true);
                }
            }
            if(key=='is_sales')
            {
                if(value==1)
                {
                    $('#is_sales').prop('checked', true);
                }
            }
            // if(key=='department')
            // {
            //     $("#edit_department").val(value.id).change();
            //     $('#selected_department_id').val(value.id);
            // }
        });
    })
</script>
<script>
    // $(document).on('change','#edit_department', function(){
    //     $('#selected_department_id').val(this.value);
    // });
</script>

<script>
    $(document).on('click','.deleteButton', function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#designations_delete_'+key).val(value);
        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addForm").validate({
            rules: {
                designation: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "<?php echo e(route('isDesignationExists')); ?>",
                        data :{
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'department': function () {
                                return $('#department').val();
                            },
                        }
                    }
                // },
                // department: {
                //     required : true,
                //     remote: {
                //         type: 'post',
                //         url: "<?php echo e(route('isDesignationExists')); ?>",
                //         data :{
                //             "_token": "<?php echo e(csrf_token()); ?>",
                //             'designation': function () {
                //                 return $('#designation').val();
                //             },
                //         }
                //     }
                }
            },
            messages: {
                designation: {
                    required : 'Designation name is required',
                    remote: 'Designation already exists',
                // },
                // department: {
                //     required : 'Department name is required',
                //     remote: 'Designation exists for this department'
                }            
            },
       });
       
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#editForm").validate({
            rules: {
                designations_name: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "<?php echo e(route('isDesignationExists')); ?>",
                        data :{
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'department': function () {
                                return $('#selected_department_id').val();
                            },
                            'id': function () {
                                return $('#designations_id').val();
                            },
                        }
                    }
                // },
                // department: {
                //     required : true,
                //     remote: {
                //         type: 'post',
                //         url: "<?php echo e(route('isDesignationExists')); ?>",
                //         data :{
                //             "_token": "<?php echo e(csrf_token()); ?>",
                //             'designation': function () {
                //                 return $('#designation').val();
                //             },
                //             'id': function () {
                //                 return $('#designations_id').val();
                //             },
                //         }
                //     }
                }
            },
            messages: {
                designations_name: {
                    required : 'Designation name is required',
                    remote: 'Designation already exists',
                // },
                // department: {
                //     required : 'Department name is required',
                //     remote: 'Designation exists for this department'
                }            
            },
       });
       
    });
</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/edbr/designation.blade.php ENDPATH**/ ?>