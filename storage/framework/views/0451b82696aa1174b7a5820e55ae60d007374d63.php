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
                                <th><?php echo ucfirst($title); ?></th>
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

    <!-- Add SubresidencyModal -->
    <div id="add_Form" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Licence</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/subresidencyInsert" method="post" id="addForm">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>Licence Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="subresidency" id="subresidency">
                        </div>
                        <div class="form-group">
                            <label>P-residency <span class="text-danger">*</span></label>
                            <select class="select form-control" name="residency" id="residency">
                                <!-- <option>Select P-residency</option> -->
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
    <!-- /Add SubresidencyModal -->
    
    <!-- Edit SubresidencyModal -->
    <div id="edit_form" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Licence</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="/subresidencyUpdate" method="post" id="editForm">
                            <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>Licence Name <span class="text-danger">*</span></label>
                            <input class="form-control" value="" id="subresidency_name" name="subresidency_name" type="text">
                            <input class="form-control" value="" id="subresidency_id" name="subresidency_id" type="hidden">
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
    <!-- /Edit SubresidencyModal -->
    
    <!-- Delete SubresidencyModal -->
    <div class="modal custom-modal fade" id="delete_form" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Subresidency</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <form action="/subresidencyDelete" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="subresidency_id" id="subresidency_delete_id">
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
    <!-- /Delete SubresidencyModal -->

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
                url: "<?php echo e(route('subresidency')); ?>",
            },

            columns: [
                {
                    "render": function() {
                        return i++;
                    }
                },
                {
                    data: 'subresidency',
                    name: 'subresidency',

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
            // console.log(key);
            $('#subresidency_'+key).val(value);
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
    $(document).on('click','.deleteButton',function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#subresidency_delete_'+key).val(value);
        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addForm").validate({
            rules: {
                subresidency: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "<?php echo e(route('isSubresidencyExists')); ?>",
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
                        url: "<?php echo e(route('isSubresidencyExists')); ?>",
                        data :{
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'subresidency': function () {
                                return $('#subresidency').val();
                            },
                        }
                    }
                }
            },
            messages: {
                subresidency: {
                    required : 'Subresidency name is required',
                    remote: 'Subresidency already exists',
                },
                residency: {
                    required : 'Company name is required',
                    remote: 'Subresidency exists for this Residency'
                }            
            },
       });
       
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#editForm").validate({
            rules: {
                subresidency_name: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "<?php echo e(route('isSubresidencyExists')); ?>",
                        data :{
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'residency': function () {
                                return $('#selected_residency_id').val();
                            },
                            'id': function () {
                                return $('#subresidency_id').val();
                            },
                        }
                    }
                },
                residency: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "<?php echo e(route('isDesignationExists')); ?>",
                        data :{
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'designation': function () {
                                return $('#subresidency_name').val();
                            },
                            'id': function () {
                                return $('#subresidency_id').val();
                            },
                        }
                    }
                }
            },
            messages: {
                subresidency_name: {
                    required : 'Subcompany name is required',
                    remote: 'Subcompany already exists',
                },
                residency: {
                    required : 'Company name is required',
                    remote: 'Subcompany exists for this Residency'
                }           
            },
       });
       
    });
</script><?php /**PATH /home/eqb1fxfgkdl8/public_html/hrm/resources/views/edbr/subresidency.blade.php ENDPATH**/ ?>