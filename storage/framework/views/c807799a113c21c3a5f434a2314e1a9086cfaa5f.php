<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid">
        <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>          
            <!-- Page Header -->
            <?php echo $__env->make('includes/breadcrumbs', ['title' => $title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3"> 
                <div class="form-group form-focus select-focus">
                    <select class="selectx form-control floating" name="holiday_year" id="holiday_year"> 
                        <?php for($y=date('Y'); $y>=2020; $y--) { ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y==date('Y'))?'selected':'';?>><?php echo $y; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 datatablex" id="dt_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title </th>
                                <th>Holiday Date</th>
                                <th>Day</th>
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
    
    <!-- Add Holiday Modal -->
    <div class="modal custom-modal fade" id="add_Form" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Holiday</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form  action="/holidayInsert" method="post" id="addForm">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>Holiday Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="title">
                        </div>
                        <div class="form-group">
                            <label>Holiday Date <span class="text-danger">*</span></label>
                            <div class="cal-iconx">
                                <input class="form-control datetimepickerx" type="date" name="holiday_date"></div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Holiday Modal -->
    
    <!-- Edit Holiday Modal -->
    <div class="modal custom-modal fade" id="edit_holiday" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Holiday</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/holidayUpdate" method="post" id="editForm">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <input class="form-control" value="" id="holiday_id" name="holiday_id" type="hidden">
                            <label>Holiday Name <span class="text-danger">*</span></label>
                            <input class="form-control" value="New Year" type="text" name="title" id="holiday_title">
                        </div>
                        <div class="form-group">
                            <label>Holiday Date <span class="text-danger">*</span></label>
                            <div class="cal-iconx"><input class="form-control datetimepicker" value="01-01-2019" type="text" name="holiday_date" id="holiday_holiday_date"></div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Holiday Modal -->

    <!-- Delete Holiday Modal -->
    <div class="modal custom-modal fade" id="delete_holiday" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Holiday</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <form action="/holidayDelete" method="post">
                            <?php echo csrf_field(); ?>
                            <input class="form-control" value="" id="holiday_delete_id" name="holiday_delete_id" type="hidden">
                            
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary continue-btn" style="width: 100%;">Delete</button>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Holiday Modal -->
    
</div>
<!-- /Page Wrapper -->


</div>
<!-- end main wrapper-->

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        // var hyear = $('#holiday_year').val();
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
                // method : 'POST',
                // data : {"_token": "<?php echo e(csrf_token()); ?>", 'year' : $('#holiday_year').val()},
                url: "<?php echo e(route('holidays')); ?>",
                data: function (d) {
                    d.hyear = $('#holiday_year').val()
                }
            },

            columns: [
                {
                    "render": function() {
                        return i++;
                    }
                },
                {
                    data: 'title',
                    name: 'title',
                },
                {
                    data: 'holiday_date',
                    name: 'holiday_date',
                },
                {
                    data: 'holiday_day',
                    name: 'holiday_day',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
        });

        $('#holiday_year').change(function(){
            table_table.draw();
        });

    });

    
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addForm").validate({
            rules: {
                title: {
                    required : true,
                    // remote: {
                    //     type: 'post',
                    //     url: "<?php echo e(route('isDepartmentExists')); ?>",
                    //     data :{
                    //         "_token": "<?php echo e(csrf_token()); ?>",
                    //     }
                    // }
                },
                holiday_date : {
                    required : true
                }
            },
            messages: {
                title: {
                    required : 'Holiday title is required',
                    // remote: 'Department already exists',
                },
                holiday_date : {
                    required : 'Date required'
                }
            },
       });
       
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        
        $("#editForm").validate({
            rules: {
                title: {
                    required : true,
                    // remote: {
                    //     type: 'post',
                    //     url: "<?php echo e(route('isDepartmentExists')); ?>",
                    //     data :{
                    //         "_token": "<?php echo e(csrf_token()); ?>",
                    //     }
                    // }
                },
                holiday_date : {
                    required : true
                }
            },
            messages: {
                title: {
                    required : 'Holiday title is required',
                    // remote: 'Department already exists',
                },
                holiday_date : {
                    required : 'Date required'
                }
            },
       });
       
    });
</script>

<script>
    $(document).on('click','.editButton',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            console.log(key);
            $('#holiday_'+key).val(value);
        });
    })
</script>


<script>
    $(document).on('click','.deleteButton',function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#holiday_delete_'+key).val(value);
        });
    })
</script><?php /**PATH /home/eqb1fxfgkdl8/public_html/hrm/resources/views/policies/holidays.blade.php ENDPATH**/ ?>