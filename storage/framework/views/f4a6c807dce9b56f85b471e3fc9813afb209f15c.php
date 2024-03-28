<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- Page Wrapper -->
<!-- Page Wrapper -->
<link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>

<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
        <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Leave Management</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leave Management</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_leave"><i class="fa fa-plus"></i> Add New Leave</a>
                </div>
                
            </div>
        </div>    
        <!-- /Page Header -->

    </div>
    <!-- /Page Content -->

    <!-- Add Selling Period Modal -->
    <div id="add_leave" class="modal custom-modal fade" role="dialog">
        <?php echo $__env->make('leave_management/leave_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <!-- /Add Selling Period Modal -->


</div>
<!-- /Page Wrapper -->


</div>


</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script type="text/javascript">
    $(document).ready(function() {
            $("#addForm").validate({
                rules: {
                    item_name: {
                        required : true,
                    },
                },
                messages: {
                    item_name: {
                        required : 'Item name is required',
                    },           
                },
        });
        
        });

        $("#add_Form").on("hidden.bs.modal", function(){
        
            $("#is_bill_count").prop('checked',false);
            $('#item_name').val('');
            $('.leave_m_title').text('Create Selling Period');
        });
    $(document).on('click','.select_change',function(){
        
        var com_id = $('.company_check:checked').val();
        var company_id = $('.company_check:checked').map(function() {
            return this.value;
        }).get().join(',');
        $('#company_id').val(company_id);
    });

    $(document).on('click','.branch_check',function(){
        var com_id = $('.company_check:checked').val();
        var br_id = $('.branch_check:checked').val();
        var sel_val = $('.branch_check:checked').map(function() {
            return this.value;
        }).get().join(',');
        
        $('#branch_id').val(sel_val);
        if(com_id != '' && br_id != ''){
            $('.add_sell_btn').show();
        }else{
            $('.add_sell_btn').hide();
        }
        
    });
    $(document).on('click','.edit_branch',function(){
        $('#add_Form').html('');
        var id= $(this).attr('data-id');
        $.ajax({
           url: "<?php echo e(route('getsellingdetaiById')); ?>",
           type: "POST",
           dataType: "json",
           data: {"_token": "<?php echo e(csrf_token()); ?>", id:id},
           success:function(response)
            {
                $('#add_Form').html(response.html).fadeIn();
            }
        });
    });

    $(document).on('click','.delete_branch',function(){
        var id= $(this).attr('data-id');
        $('#selling_delete_id').val(id);
    });

    $('.stable').on('click','#flexSwitchCheckChecked', function (e) {
		var url = $(this).attr("data-url");
		 location.href=url;
	});

    $("#add_Form").on("hidden.bs.modal", function(){
        
        $("#is_bill_count").prop('checked',false);
        $('#item_name').val('');
        $('.leave_m_title').text('Create Selling Period');
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
</script>
<?php /**PATH C:\wamp64_new\www\hrm\resources\views/leave_management/index.blade.php ENDPATH**/ ?>