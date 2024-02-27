<head> 
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
    </script>
</head>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title"><?php echo e((isset($data) && !empty($data->id)) ? 'Edit' : 'Create'); ?> Heading</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="<?php echo e(route('tracking_heading.store')); ?>" method="post" id="addForm">
            <?php echo csrf_field(); ?>
                <input type="hidden" name="company_id" id="company_id">
                <input type="hidden" name="branch_id" id="branch_id">
                <input type="hidden" name="sell_id" id="sell_id">
                <input type="hidden" name="tracking_id" id="tracking_id" value="<?php echo e($data->id ?? ''); ?>">
                <div class="form-group">
                    <label>Item Name <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="item_name" id="item_name" value="<?php echo e($data->title ?? ''); ?>">
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/selling_management/tracking_heading_modal.blade.php ENDPATH**/ ?>