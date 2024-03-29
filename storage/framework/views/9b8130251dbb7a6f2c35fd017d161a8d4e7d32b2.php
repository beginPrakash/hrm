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
                                <h3 class="page-title">Leaves Hierarchy</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Leaves Hierarchy</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_leave"><i class="fa fa-plus"></i> Add Leave Hierarchy</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class=" table table-bordered table-striped table-hover datatable datatable-adminleave">
                                    <thead>
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Department</th>
                                            <th>Title</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($leaveApplications) && count($leaveApplications) > 0): ?>
                                            <?php $__currentLoopData = $leaveApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e((isset($v->leaves_leavetype) && !empty($v->leaves_leavetype)) ? $v->leaves_leavetype->name : ''); ?></td>
                                                <td><?php echo e((isset($v->department_detail) && !empty($v->department_detail)) ? $v->department_detail->name : ''); ?></td>
                                                <td><?php echo e((isset($v->designation_detail) && !empty($v->designation_detail)) ? $v->designation_detail->name : ''); ?></td>
                                                <td>
                                                    <div class="pull-right">
                                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_leave" class="action-icon edit_hierarchy" data-id="<?php echo e($v->id); ?>"><i class="fa fa-pencil"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Page Content -->
                
                <!-- Add Leave Modal -->
                <div id="add_leave" class="modal custom-modal fade" aria-labelledby="myModalLabel" aria-hidden="true">
                    <?php echo $__env->make('policies/admin_leave_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <!-- /Add Leave Modal -->

                
            </div>
            <!-- /Page Wrapper -->


<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select').select2({
                //-^^^^^^^^--- update here
                minimumResultsForSearch: 1,
                //allowClear: true,
                width: '100%',
                dropdownParent: $('#add_leave')
            });
        // Add More Dept
        $('.add_more_dept_btn').click(function() {
           
            var element = $('.add_new_dept:first').clone();
            var j = $('.add_dept_div').not('.d-none').length;
            element.find('.dept_select').val('');
            element.find('.title_select').val('');
            element.removeClass('d-none');
            element.find('.dept_select').addClass('select');
            element.find('.title_select').addClass('select');
            element.insertAfter($(this).parents().find('.add_dept_div:last'));
            $('.select').select2({
                //-^^^^^^^^--- update here
                minimumResultsForSearch: 1,
                //allowClear: true,
                width: '100%',
                dropdownParent: $(this).parent()
            });
            if(j>=1){
                $('.dept_select:last').attr('id','dept_select'+j);
                $('.title_select:last').attr('id','title_select'+j);
                $('.dept_select:last').attr('name','sub_department[]');
                $('.title_select:last').attr('name','sub_title[]');
            }
            if(j >= 1){
                  $(".add_more_dept_btn:last").remove();
                  $('.add_btn_div:last').append('<button type="button" class="btn btn-primary plus-minus remove_dept_btn"><i class="fas fa-minus"></i></button>');
              }
            j++;
            if ($('.agenda_div').length > 1) {
                $('.agenda_div').find('.remove_agenda').show();
            }
            
        });

        //remove row when click remove button
        $(document).on('click','.remove_dept_btn',function(){
            $(this).closest('div').parent().remove();
        });

        $("#admin_leaves_form").validate({
            rules: {
                leave_type: {
                    required : true},
                main_department:  {
                    required : false},
                main_title:  {
                    required : true},
                // 'sub_department[]':  {
                //     required : true},
                // 'sub_title[]':  {
                //     required : true},
            },
            messages: {
                leave_type: {
                    required : 'Leave Type is required',
                },
                main_department: {
                    required : 'Please select department',
                }
                ,
                main_title: {
                    required : 'Please select title',
                },
                // 'sub_department[]': {
                //     required : 'Please select department',
                // },
                // 'sub_title[]': {
                //     required : 'Please select title',
                // }
            },
            errorPlacement: function (error, element) {
                if (element.prop("type") == "text") {
                    error.insertAfter(element);
                } else {
                    error.insertAfter(element.parent());
                }
            },
       });
       
    });

    $(document).on('change', '.title_select', function() {
            // for department hide/show
            var prio = $(this).find(":selected").data("priority");
            $(this).parent().parent().parent().find('.department_div').show();
            if(prio == '1' || prio == '2')
            {
                //console.log($(this).parent().parent().parent().find('.department_div'));
                $(this).parent().parent().parent().find('.department_div').hide();
                $(this).find('.dep_hid').val(1);
            }

            //for multi user check
        });

        $(document).on('change', '.main_title', function() {
            // for department hide/show
            var prio = $(this).find(":selected").data("priority");
            $('.main_department').show();
            if(prio == '1' || prio == '2')
            {
                $('#main_department').val('').trigger('change');
                $('.main_department').hide();
                $(this).find('.dep_hid').val(1);
            }

            //for multi user check
        });

    $(document).on('click','.edit_hierarchy',function(){
        $('#add_leave').html('');
        var id= $(this).attr('data-id');
        $.ajax({
           url: "<?php echo e(route('getadminLeaveDetailsById')); ?>",
           type: "POST",
           dataType: "json",
           data: {"_token": "<?php echo e(csrf_token()); ?>", id:id},
           success:function(response)
            {
                $('#add_leave').html(response.html).fadeIn();
            }
        });
    });
    $("#add_leave").on("hidden.bs.modal", function(){
        
        $(".select").val(null).trigger("change");
        $('.leave_id').val('');
        $('.add_dept_div').slice(1).remove();
        $('.leave_m_title').text('Add Leave Hierarchy');
    });

  
</script>

<script>
    $(document).on('click','.approveButton',function(){
        var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#leave_id').val(lid);
        $('#approval_by').val(id);
    })
</script>

<script>
    $(document).on('click','.rejectButton',function(){
        var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#reject_leave_id').val(lid);
        $('#reject_by').val(id);
    })
</script>

<script>
    $(document).on('click','.cancelButton',function(){
        // var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#cancel_leave_id').val(lid);
        // $('#cancel_by').val(id);
    })
</script>

<script type="text/javascript">
    $(document).on('change','#leave_type, #leave_leave_type',function(){
        var leave_type = $(this).val();
        var max_leaves = $(this).find(':selected').data('id');
        $('#addLeaveBtn').attr('disabled', false);
        $('#rl_count_err').text('');
        $.ajax({
           url: '/getLeaveDetails/',
           type: "POST",
           dataType: "json",
           data: {"_token": "<?php echo e(csrf_token()); ?>", leave_type:leave_type},
           success:function(response)
            {
                //response = taken leaves
                var remaining_leave = max_leaves - response;
                $('#remaining_leaves').val(remaining_leave);
                if(remaining_leave == 0)
                {
                    $('#addLeaveBtn').attr('disabled', true);
                    $('#rl_count_err').text('Insufficient no of leaves');
                }
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('change','#from_date, #to_date',function(){
        var from_date = $('#from_date').val();
        $('#to_date').attr('min', from_date);
        var to_date = $('#to_date').val();

        var dt1 = new Date(from_date);
        var dt2 = new Date(to_date);
 
        var time_difference = dt2.getTime() - dt1.getTime();
        var result = time_difference / (1000 * 60 * 60 * 24);

        var no_days = (result >= 0 )?result+1:0;
        $('#no_of_days').val(no_days);
    });

    $(document).on('change','#leave_leave_from, #leave_leave_to',function(){
        var from_date = $('#leave_leave_from').val();
        $('#leave_leave_to').attr('min', from_date);
        var to_date = $('#leave_leave_to').val();

        var dt1 = new Date(from_date);
        var dt2 = new Date(to_date);
 
        var time_difference = dt2.getTime() - dt1.getTime();
        var result = time_difference / (1000 * 60 * 60 * 24);
  
        var no_days = (result >= 0 )?result+1:0;
        $('#leave_leave_days').val(no_days);
    });
</script>

<script>
    $(document).on('click','.editButton',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            // console.log(key);
            $('#leave_'+key).val(value);
            if(key == 'leave_type')
            {
                $("#leave_leave_type").val(value).change();
            }
        });
    })
</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/policies/admin_leaves.blade.php ENDPATH**/ ?>