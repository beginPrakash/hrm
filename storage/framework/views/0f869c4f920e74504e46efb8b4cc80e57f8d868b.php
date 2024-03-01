<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- Page Wrapper -->
<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
        <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Upselling Score Heading</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Upselling Score Heading</li>
                    </ul>
                </div>
                
            </div>
        </div>    
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="<?php echo e(route('selling_period.list')); ?>" id="search_form">
            <?php echo csrf_field(); ?>
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" 
                                    id="dropdownMenu1" data-toggle="dropdown" 
                                    aria-haspopup="true" aria-expanded="true">
                                Select Company
                            
                            </button>
                            <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
                                <?php if(isset($company_list) && count($company_list) > 0): ?>  
                                    <?php $__currentLoopData = $company_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                        <label>
                                            <input type="checkbox" class="company_check" name="company[]" value="<?php echo e($key); ?>"><?php echo e($val); ?>

                                        </label>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </ul>
                        </div> 
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle branch_checklist" type="button" 
                                    id="dropdownMenu1" data-toggle="dropdown" 
                                    aria-haspopup="true" aria-expanded="true">
                                Select Branch
                            </button>
                           
                        </div> 
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle sells_checklist" type="button" 
                                id="dropdownMenu3" data-toggle="dropdown" 
                                aria-haspopup="true" aria-expanded="true">
                            Select Selling Period
                        
                        </button>
                       
                        
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2 add_sell_btn" style="display:none">
                    <a href="#" class="btn add-btn" data-toggle="modal" id="fwb" data-target="#add_Form"><i class="fa fa-plus"></i> Add Heading</a>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <table class="table table-striped custom-table mb-0 datatable stable">
                        <thead>
                            <tr>
                                <th width="30px">Company </th>
                                <th> Branch </th>
                                <th> Selling Period </th>
                                <th>Title </th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($selling_data) && count($selling_data) > 0): ?>
                                <?php $__currentLoopData = $selling_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e((isset($val->company_detail) && !empty($val->company_detail->name)) ? $val->company_detail->name : ''); ?></td>
                                        <td><?php echo e((isset($val->branch_detail) && !empty($val->branch_detail->name)) ? $val->branch_detail->name : ''); ?></td>
                                        <td><?php echo e((isset($val->sellp_detail) && !empty($val->sellp_detail->item_name)) ? $val->sellp_detail->item_name : ''); ?></td>
                                        <td><?php echo e($val->title); ?></td>
                                        
                                        <td>
                                            <div class="pull-right begin_actions">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" data-url="<?php echo e(route('upselling_heading.statuschange',array($val->id,$val->is_show ?? 0))); ?>" id="flexSwitchCheckChecked" <?php echo e((!empty($val->is_show)) ? 'checked' : ''); ?>>
                                                </div>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#add_Form" class="action-icon edit_branch" data-id="<?php echo e($val->id); ?>"><i class="fa fa-pencil"></i></a>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#delete_form" class="action-icon delete_branch" data-id="<?php echo e($val->id); ?>"><i class="fa fa-trash"></i></a>
                                                
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

    <!-- Add Selling Period Modal -->
    <div id="add_Form" class="modal custom-modal fade" role="dialog">
        <?php echo $__env->make('up_selling_management/tracking_heading_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <!-- /Add Selling Period Modal -->
    
    <!-- Delete Selling Period Modal -->
    <div class="modal custom-modal fade" id="delete_form" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Tracking Heading</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                              <form action="<?php echo e(route('upselling_heading.delete')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="selling_id" id="selling_delete_id">
                                    <button type="submit" class="btn btn-primary btn-large continue-btn" style="width: 100%;">Delete</button>
                              </form>
                                </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Selling Period Modal -->

</div>
<!-- /Page Wrapper -->


</div>


</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<link href="<?php echo e(asset('assets/css/bootstrap-new.css')); ?>" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
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
    
        $('#item_name').val('');
        $('.leave_m_title').text('Create Heading');
    });
    $(document).on('click','.sells_check',function(){
        var com_id = $('.company_check:checked').val();
        var br_id = $('.branch_check:checked').val();
        var sell_id = $('.sells_check:checked').val();
        var sel_val = $('.sells_check:checked').map(function() {
            return this.value;
        }).get().join(',');
        
        $('#sell_id').val(sel_val);
        if(com_id != '' && br_id != '' && sell_id != ''){
            $('.add_sell_btn').show();
        }else{
            $('.add_sell_btn').hide();
        }
        
    });

    $(document).on('click','.company_check',function(){
        $('.branch_menu').remove();
        var sel_val = $('.company_check:checked').map(function() {
            return this.value;
        }).get().join(',');
        $('#company_id').val(sel_val);
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

    $(document).on('click','.branch_check',function(){
        $('.sells_menu').remove();
        var sel_val = $('.branch_check:checked').map(function() {
            return this.value;
        }).get().join(',');
        var company_id = $('.company_check:checked').map(function() {
            return this.value;
        }).get().join(',');
        $('#branch_id').val(sel_val);
        $.ajax({
            url: "<?php echo e(route('upselling_heading.upsellplistbycompany')); ?>",
            type: "POST",
            dataType: "json",
            data: {"_token": "<?php echo e(csrf_token()); ?>", sel_val:sel_val,company_id:company_id},
            success:function(response)
                {
                    $('.sells_checklist').after(response.html).fadeIn();
                }
        });
    });

    $(document).on('click','.edit_branch',function(){
        $('#add_Form').html('');
        var id= $(this).attr('data-id');
        $.ajax({
           url: "<?php echo e(route('getupsetrackingdetaiById')); ?>",
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

</script>
<?php /**PATH C:\wamp64_new\www\hrm\resources\views/up_selling_management/tracking_heading.blade.php ENDPATH**/ ?>