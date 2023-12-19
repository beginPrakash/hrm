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
            <h5 class="modal-title leave_m_title"><?php echo e((isset($residency->id) && !empty($residency->id)) ? 'Edit' : 'Add'); ?> Company</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <form action="<?php echo e(route('company.store')); ?>" method="POST" enctype="multipart/form-data" id="company_form">
            <input type="hidden" name="id" value="<?php echo e($residency->id ?? ''); ?>" class="company_id_hid">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Company Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name" value="<?php echo e($residency->name ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Logo</label>
                        <div class="image-upload">
                            <label for="file-input4">
                                <img src="<?php echo (isset($residency) && $residency->logo!=NULL)?'../uploads/logo/'.$residency->logo:""; ?>" id="img1"/>
                            </label>
                            <input id="file-input1" name="image1" id="logo" type="file" onchange="previewFile(this, 'img1');"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" type="text" name="address" id="address"><?php echo e($residency->address ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Country</label>
                        <input type="text" class="form-control" name="country" value="<?php echo e($residency->country ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" class="form-control" name="city" value="<?php echo e($residency->city ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>State/Province</label>
                        <input type="text" class="form-control" name="state" value="<?php echo e($residency->state ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label>Postal Code</label>
                        <input type="text" class="form-control digitsOnly" name="postal_code" value="<?php echo e($residency->postal_code ?? ''); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo e($residency->email ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control digitsOnly" name="phone_number" value="<?php echo e($residency->phone_number ?? ''); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Fax</label>
                        <input type="text" class="form-control" name="fax" value="<?php echo e($residency->fax ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Website Url</label>
                        <input type="text" class="form-control" name="website" value="<?php echo e($residency->website ?? ''); ?>">
                    </div>
                </div>
            </div>
            <div class="submit-section">
                <button type="submit" name="update" class="btn btn-primary submit-btn">Update</button>
            </div>
        </form> 
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/settings/company_modal.blade.php ENDPATH**/ ?>