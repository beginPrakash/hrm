<script type="text/javascript" src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
<script>
    $("#trans_form").validate({
        rules: {
            car_name: {
                required : true
            },
            colour: {
                required : true
            },
            model: {
                required : true
            },
            license_number: {
                required : true
            },
            under_company: {
                required : true
            },
            under_subcompany: {
                required : true
            },
        },
        messages: {
            car_name: {
                required : 'Please enter car name',
            },
            colour: {
                required : 'Please enter colour',
            },
            model: {
                required : 'Please enter model',
            },
            license_number: {
                required : 'Please enter license number',
            },
            under_company: {
                required : 'Please select company',
            },
            under_subcompany: {
                required : 'Please select subcompany',
            },
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

    var len = 0;
    $('#doc_addmore').click(function()
    {
        len++;// = $('.rowdiv').length;
        var cl = "doc_file_"+len;
        $('#div_doc_addmore').before('<div class="row rowdiv" id="rowdiv'+len+'"><div class="col-md-5"><div class="form-group"><input class="form-control" id="doc_file_'+len+'" onchange="Filevalidation(this,'+len+')" type="file" name="doc_file[]" value=""></div></div><div class="col-md-1"><span class="mt-4 trashDiv" onclick="removeDiv('+len+')"><i class="fa fa-trash text-danger"></i></span></div></div>');
    });

    function removeDiv(tid) {
        $('#rowdiv'+tid).remove();
    }

    Filevalidation = (input,id) => {
            $('.file_error').html('');
            const fi = $(input).get(0).files[0];
            // Check if any file is selected.
            if (fi) {
                    if(fi.type == 'application/pdf'){
                        const fsize = fi.size;
                        const file = Math.round((fsize / 1024));
                        //console.log(file);
                        // The size of the file.
                        if (file >= 4096) {
                            $('#doc_file_'+id).after("<span class='error file_error'>File too Big, please select a file less than 4mb</span>"); 
                            $('#doc_file_'+id).val('');
                        }
                    }
                    else{
                        $('#doc_file_'+id).after("<span class='error file_error'>Select Only PDF file</span>"); 
                        $('#doc_file_'+id).val('');
                    }
            }
        }
</script>

<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title"><?php echo e((isset($trans_data->id) && !empty($trans_data->id)) ? 'Edit' : 'Add'); ?> Transportation</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <form action="<?php echo e(route('transportation.store')); ?>" method="POST" enctype="multipart/form-data" id="trans_form">
            <input type="hidden" name="id" value="<?php echo e($trans_data->id ?? ''); ?>" class="company_id_hid">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Car Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="car_name" value="<?php echo e($trans_data->car_name ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Logo</label>
                        <div class="image-upload">
                            <label for="file-input4">
                                <img src="<?php echo (isset($trans_data) && $trans_data->logo!=NULL)?'/uploads/logo/'.$trans_data->logo:""; ?>" id="img1"/>
                            </label>
                            <input id="file-input1" name="image1" id="logo" type="file" onchange="previewFile(this, 'img1');"/><?php echo e($trans_data->logo ?? ''); ?>

                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Colour<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="colour" value="<?php echo e($trans_data->colour ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Model<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="model" value="<?php echo e($trans_data->model ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>License Number<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="license_no" value="<?php echo e($trans_data->license_no ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks"><?php echo e($trans_data->remarks ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Driver</label>
                        <input type="text" class="form-control" name="driver" value="<?php echo e($trans_data->driver ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Tag</label>
                        <input type="text" class="form-control" name="tag" value="<?php echo e($trans_data->tag ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Under Company<span class="text-danger">*</span></label>
                        <select class="select" name="under_company">
                            <option value="">Select</option>
                            <?php if(isset($company_list) && count($company_list) > 0): ?>
                                <?php $__currentLoopData = $company_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val->id); ?>" <?php echo e((isset($trans_data->under_company) && ($val->id == $trans_data->under_company)) ? 'selected' : ''); ?>><?php echo e($val->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Under SubCompany<span class="text-danger">*</span></label>
                        <select class="select" name="under_subcompany">
                            <option value="">Select</option>
                            <?php if(isset($company_list) && count($company_list) > 0): ?>
                                <?php $__currentLoopData = $company_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val->id); ?>" <?php echo e((isset($trans_data->under_subcompany) && ($val->id == $trans_data->under_subcompany)) ? 'selected' : ''); ?>><?php echo e($val->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="submit-section">
                <button type="submit" name="update" class="btn btn-primary submit-btn">Submit</button>
            </div>
        </form> 
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/transportation/transportation_modal.blade.php ENDPATH**/ ?>