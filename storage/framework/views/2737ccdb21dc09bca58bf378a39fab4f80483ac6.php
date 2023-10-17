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
                    <table class="table table-striped custom-table mb-0" id="dt_table">
                        <thead>
                            <tr>
                                <th style="width: 30px;">#</th>
                                <th>Branch </th>
                                <th>Company </th>
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

    <!-- Add Branch Modal -->
    <div id="add_Form" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Branch</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/branchInsert" method="post" id="addForm">
                    <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>Branch Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="branch" id="branch">
                        </div>
                        <div class="form-group">
                            <label>Company <span class="text-danger">*</span></label>
                            <select class="select form-control" name="residency" id="residency">
                                <?php
                                if(isset($allCompanies))
                                {
                                    foreach ($allCompanies as $crow) 
                                    {
                                        ?>
                                        <option value="<?php echo $crow->id; ?>"><?php echo $crow->name; ?></option>
                                        <?php 
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Branch Modal -->
    
    <!-- Edit Branch Modal -->
    <div id="edit_form" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Branch</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="/branchUpdate" method="post" id="editForm">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>Branch Name <span class="text-danger">*</span></label>
                            <input class="form-control" value="" id="branch_name" name="branch_name" type="text">
                            <input class="form-control" value="" id="branch_id" name="branch_id" type="hidden">
                        </div>
                        <div class="form-group">
                            <label>Residency <span class="text-danger">*</span></label>
                            <select class="form-control select" id="edit_residency" name="residency">
                                <!-- <option value="">Select Residency</option> -->
                                <?php
                                if(isset($allCompanies))
                                {
                                    foreach ($allCompanies as $derow) 
                                    {
                                        ?>
                                        <option value="<?php echo $derow->id; ?>"><?php echo $derow->name; ?></option>
                                        <?php 
                                    }
                                }
                                ?>
                            </select>
                            <input class="form-control" value="" id="selected_residency_id" name="selected_residency_id" type="hidden" value="">
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Branch Modal -->
    
    <!-- Delete Branch Modal -->
    <div class="modal custom-modal fade" id="delete_form" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Branch</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                              <form action="/branchDelete" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="branch_id" id="branch_delete_id">
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
    <!-- /Delete Branch Modal -->

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
                url: "<?php echo e(route('branch')); ?>",
            },

            columns: [
                {
                    "render": function() {
                        return i++;
                    }
                },
                {
                    data: 'branch',
                    name: 'branch',

                },
                {
                    data: 'residency',
                    name: 'residency',

                },
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
    $(document).on('click','.editButton',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            $('#branch_'+key).val(value);
            if(key=='residency')
            {
                $("#edit_residency").val(value.id).change();
                $('#selected_residency_id').val(value.id);
            }
        });
    })
</script>
<script>
    $(document).on('change','#edit_residency', function(){
        $('#selected_residency_id').val(this.value);
    });
</script>

<script>
    $(document).on('click','.deleteButton', function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#branch_delete_'+key).val(value);
        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addForm").validate({
            rules: {
                branch: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "<?php echo e(route('isBranchExists')); ?>",
                        data :{
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'residency': function () {
                                return $('#residency').val();
                            },
                        }
                    }
                },
                residency: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "<?php echo e(route('isBranchExists')); ?>",
                        data :{
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'branch': function () {
                                return $('#branch').val();
                            },
                        }
                    }
                }
            },
            messages: {
                branch: {
                    required : 'Branch name is required',
                    remote: 'Branch already exists',
                },
                residency: {
                    required : 'Company name is required',
                    remote: 'Branch exists for this Residency'
                }            
            },
       });
       
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#editForm").validate({
            rules: {
                branch_name: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "<?php echo e(route('isBranchExists')); ?>",
                        data :{
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'residency': function () {
                                return $('#selected_residency_id').val();
                            },
                            'id': function () {
                                return $('#branch_id').val();
                            },
                        }
                    }
                },
                residency: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "<?php echo e(route('isBranchExists')); ?>",
                        data :{
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'designation': function () {
                                return $('#branch_name').val();
                            },
                            'id': function () {
                                return $('#branch_id').val();
                            },
                        }
                    }
                }
            },
            messages: {
                branch_name: {
                    required : 'Branch name is required',
                    remote: 'Branch already exists',
                },
                residency: {
                    required : 'Company name is required',
                    remote: 'Branch exists for this Residency'
                }           
            },
       });
       
    });
</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/edbr/branch.blade.php ENDPATH**/ ?>