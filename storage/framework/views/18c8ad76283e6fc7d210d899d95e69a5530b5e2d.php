<script type="text/javascript" src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
<script>
    $("#company_form").validate({
        rules: {
            name: {
                required : true},
        },
        messages: {
            name: {
                required : 'Please enter company name',
            }
        },
        errorPlacement: function (error, element) {
            if (element.prop("type") == "text" || element.prop("type") == "textarea") {
                error.insertAfter(element);
            } else {
                error.insertAfter(element.parent());
            }
        },
    });

    $('.digitsOnly').keypress(function(event){
        if(event.which !=8 && isNaN(String.fromCharCode(event.which))){
            event.preventDefault();
        }
    });
</script>

<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title"><?php echo e((isset($residency->id) && !empty($residency->id)) ? 'Edit' : 'Add'); ?> Document</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <form action="<?php echo e(route('document.store')); ?>" method="POST" enctype="multipart/form-data" id="document_form">
            <input type="hidden" name="id" value="<?php echo e($residency->id ?? ''); ?>" class="doc_id_hid">
            <input type="hidden" name="company_id" value="<?php echo e($company_detail->id ?? ''); ?>">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Registration Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="reg_name" value="<?php echo e($residency->reg_name ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Registration Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="reg_no" value="<?php echo e($residency->reg_no ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Civil Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="civil_no" value="<?php echo e($residency->civil_no ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Issuing Date<span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="issuing_date" value="<?php echo e($residency->issuing_date ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Expiry Date<span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="expiry_date" value="<?php echo e($residency->expiry_date ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Alert Days<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="alert_days" value="<?php echo e($residency->alert_days ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input class="form-control" type="text" name="remarks" value="<?php echo e($residency->remarks ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Cost<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="cost" value="<?php echo e($residency->cost ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Upload File</label>
                        <div class="image-upload">
                            <label for="file-input4">
                                <img src="<?php echo (isset($residency) && $residency->logo!=NULL)?'../uploads/logo/'.$residency->logo:""; ?>" id="img1"/>
                            </label>
                            <input id="file-input1" name="file" id="logo" type="file" onchange="previewFile(this, 'img1');"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="submit-section">
                <button type="submit" class="btn btn-primary submit-btn">Update</button>
            </div>
        </form> 
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/settings/document_modal.blade.php ENDPATH**/ ?>