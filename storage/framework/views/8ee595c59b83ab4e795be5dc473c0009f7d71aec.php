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
        
            $("#is_bill_count").prop('checked',false);
            $('#item_name').val('');
            $('.leave_m_title').text('Create Selling Period');
        });
    </script>
</head>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title"><?php echo e((isset($data) && !empty($data->id)) ? 'Change' : 'Create'); ?> Selling Period</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="<?php echo e(route('up_selling_period.store')); ?>" method="post" id="addForm">
            <?php echo csrf_field(); ?>
                <input type="hidden" name="company_id" id="company_id">
                <input type="hidden" name="branch_id" id="branch_id">
                <input type="hidden" name="selling_id" id="selling_id" value="<?php echo e($data->id ?? ''); ?>">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="item_name" id="item_name" value="<?php echo e($data->item_name ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_bill_count" id="is_bill_count" value="1" <?php if(isset($data->is_bill_count) && $data->is_bill_count==1): ?> checked <?php endif; ?>>
                                <label class="form-check-label" for="is_bill_count">
                                    Bill Count
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_cc" id="is_cc" value="1" <?php if(isset($data->is_cc) && $data->is_cc==1): ?> checked <?php endif; ?>>
                                <label class="form-check-label" for="is_cc">
                                    CC
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/up_selling_management/selling_period_modal.blade.php ENDPATH**/ ?>