<script type="text/javascript" src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
<script>
    var reg_url = "<?php echo e(route('getRegtype')); ?>";
    // $('#reg_type').tokenfield({
    //     autocomplete :{
    //         source: function(request, response)
    //         {
    //             jQuery.get(reg_url, {
    //                 query : request.term
    //             }, function(data){
    //                 data = JSON.parse(data);
    //                 response(data);
    //             });
    //         },

    //         delay: 100
    //     }
    // });

    $("#reg_type").autocomplete({
        source: function(request, response)
            {
                jQuery.get(reg_url, {
                    query : request.term
                }, function(data){
                    data = JSON.parse(data);
                    response(data);
                });
            },
    });

    $(document).on('click','.close_reg_data',function(){
        $(this).parent().remove();
        var doc_id= $(this).attr('data-docid');
        var reg_id= $(this).attr('data-reg_id');
        $.ajax({
        url: "<?php echo e(route('deletetransregtypebydocument')); ?>",
        type: "POST",
        dataType: "json",
        data: {"_token": "<?php echo e(csrf_token()); ?>", doc_id:doc_id,reg_id:reg_id},
        success:function(response)
            {
                $('#add_document').html(response.html).fadeIn();
            }
        });
    });

    var reghtml = '<?php echo e($reg_html ?? ''); ?>';
    //$('#reg_type').parent('.tokenfield').prepend($('.regtype_data').text());
    
    $("#document_form").validate({
        rules: {
            doc_number: {
                required : true
            },
            doc_name: {
                required : true
            },
            expiry_date: {
                required : true
            },
            alert_days: {
                required : true
            },
            cost: {
                required : true
            },
            doc_file: {
                required : true
            },
        },
        messages: {
            doc_number: {
                required : "Please enter document number"
            },
            doc_name: {
                required : "Please enter document name"
            },
            expiry_date: {
                required : "Please select expiry date"
            },
            alert_days: {
                required : "Please enter alert days"
            },
            cost: {
                required : "Please enter cost"
            },
            doc_file: {
                required : "Please select document"
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
        $('#div_doc_addmore').before('<div class="row rowdiv" id="rowdiv'+len+'"><div class="col-md-5"><div class="form-group"><input class="form-control" type="file" id="doc_file_'+len+'" name="doc_file[]" value="" onchange="Filevalidation(this,'+len+')"></div></div><div class="col-md-1"><span class="mt-4 trashDiv" onclick="removeDiv('+len+')"><i class="fa fa-trash text-danger"></i></span></div></div>');
    });
</script>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title"><?php echo e((isset($doc_data->id) && !empty($doc_data->id)) ? 'Edit' : 'Add'); ?> Document</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <form action="<?php echo e(route('transdoc.store')); ?>" method="POST" enctype="multipart/form-data" id="document_form">
            <input type="hidden" name="id" value="<?php echo e($doc_data->id ?? ''); ?>" class="doc_id_hid">
            <?php if(isset($doc_data) && !empty($doc_data)): ?>
                <input type="hidden" name="transpo_id" value="<?php echo e($doc_data->transportation_id ?? ''); ?>">
                <div class="regtype_data d-none">
                    <?php echo e($reg_html ?? ''); ?>

                </div>
            <?php else: ?>
                <input type="hidden" name="transpo_id" value="<?php echo e($trans_detail->id ?? ''); ?>">
            <?php endif; ?>
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Document Number<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="doc_number" value="<?php echo e($doc_data->doc_number ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Document Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="doc_name" value="<?php echo e($doc_data->doc_name ?? ''); ?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Registration Type</label>
                        <div class="input-group regtype_main_div">
                            <input type="text" id="reg_type" name="reg_type" placeholder="" autocomplete="off" class="form-control input-lg" value="<?php echo e((isset($doc_data->regis_type) && !empty($doc_data->regis_type)) ? $doc_data->regis_type->name : ''); ?>"/>
                        </div>
                        <br />
                        <span id="country_name"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Document Expiry Date<span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="expiry_date" value="<?php echo e($doc_data->expiry_date ?? ''); ?>">
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Alert Days<span class="text-danger">*</span></label>
                        <input class="form-control digitsOnly" type="text" name="alert_days" value="<?php echo e($doc_data->alert_days ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Cost<span class="text-danger">*</span></label>
                        <input class="form-control allowfloatnumber" type="text" name="cost" value="<?php echo e($doc_data->cost ?? ''); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Upload File</label>
                        <div class="image-upload">
                            <label for="file-input4">
                                <img src="<?php echo (isset($residency) && $residency->logo!=NULL)?'../uploads/logo/'.$residency->logo:""; ?>" id="img1"/>
                            </label>
                            <input id="doc_file_0" name="doc_file[]" type="file" class="doc_file" onchange="Filevalidation(this,0)"/>
                        </div>
                    </div>
                </div>
                <div id="div_doc_addmore"></div> 
                <?php if(isset($doc_files) && count($doc_files) > 0): ?>
                    <table class="table doc_table">
                        <tr>
                            <th>Title</th>
                            <th>File</th>
                            <th class="text-end">Action</th>
                        </tr>
                        <?php $__currentLoopData = $doc_files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="doc_<?php echo e($val->id); ?>">
                                <td>
                                    <small class="block text-ellipsis">
                                        <span class="text-muted">Uploaded on : <?php echo e(dateDisplayFormat($val->created_at)); ?></span>
                                    </small>
                                </td>
                                <td>
                                    <a href="<?php echo e(asset('uploads/transportation/'.$val->transpo_file)); ?>" class="text-info" target="_blank"><i class="fa fa-file"></i><?php //echo $edoc->document_file; ?></a>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a class="dropdown-item deleteDocButton" data-data="<?php echo e($val->id); ?>"><i class="fa fa-trash-o m-r-5 text-danger"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-sm btn-success pull-right" id="doc_addmore">Add More</button>
                </div>
            </div> 
            <div class="submit-section">
                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
            </div>
        </form> 
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/transportation/document_modal.blade.php ENDPATH**/ ?>