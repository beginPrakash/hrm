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
                    <h3 class="page-title">Company Detail</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Company Detail</li>
                    </ul>
                </div>
            </div>
        </div>      

        <!-- /Page Header -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="#"><img alt="" src="<?php echo e(($company_detail->logo!=null)? asset('uploads/logo/'.$company_detail->logo):asset('assets/img/profiles/avatar.png')); ?>"></a>
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-0"><?php echo e($company_detail->name); ?>

                                                <?php echo ($company_detail->status=='inactive')?'<span class="badge bg-inverse-danger">Inactivated</span>':'<span class="badge bg-inverse-success">Active</span>'; ?>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <?php if(isset($company_detail->phone_number) && !empty($company_detail->phone_number)): ?>
                                                <li>
                                                    <div class="title">Phone:</div>
                                                    <div class="text"><?php echo e($company_detail->phone_number ?? "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($company_detail->email) && !empty($company_detail->email)): ?>
                                            <li>
                                                <div class="title">Email:</div>
                                                <div class="text"><a href="#"><?php echo e($company_detail->email ?? "--"); ?></a></div>
                                            </li>
                                            <?php endif; ?>
                                            <?php if(isset($company_detail->address) && !empty($company_detail->address)): ?>
                                                <li>
                                                    <div class="title">Address:</div>
                                                    <div class="text"><?php echo e($company_detail->address ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($company_detail->country) && !empty($company_detail->country)): ?>
                                                <li>
                                                    <div class="title">Country:</div>
                                                    <div class="text"><?php echo e($company_detail->country ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($company_detail->city) && !empty($company_detail->city)): ?>
                                                <li>
                                                    <div class="title">City:</div>
                                                    <div class="text"><?php echo e($company_detail->city ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($company_detail->state) && !empty($company_detail->state)): ?>
                                                <li>
                                                    <div class="title">State:</div>
                                                    <div class="text"><?php echo e($company_detail->state ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($company_detail->postal_code) && !empty($company_detail->postal_code)): ?>
                                                <li>
                                                    <div class="title">Postal Code:</div>
                                                    <div class="text"><?php echo e($company_detail->postal_code ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($company_detail->fax) && !empty($company_detail->fax)): ?>
                                                <li>
                                                    <div class="title">Fax:</div>
                                                    <div class="text"><?php echo e($company_detail->fax ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(isset($company_detail->website) && !empty($company_detail->website)): ?>
                                                <li>
                                                    <div class="title">Website:</div>
                                                    <div class="text"><?php echo e($company_detail->website ??  "--"); ?></div>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="pro-edit"><a data-bs-toggle="modal" data-bs-target="#add_company" class="edit-icon editButton" data-id="<?php echo e($company_detail->id); ?>" ><i class="fa fa-pencil"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card tab-box mb-0">
            <div class="row user-tabs ">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a href="#document" data-bs-toggle="tab" class="nav-link active">Document</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content">
        <!-- Projects Tab -->
        <div class="tab-pane fade active show" id="document">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Documents <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#add_document"><i class="fa fa-plus"></i></a></h3>
                                <div class="dropdown profile-action">
                                </div>
                                <p class="text-muted">
                                <div class="row staff-grid-row pt-4">
                                    <?php if(isset($documents) && count($documents) > 0): ?>
                                        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3 m-ht-3">
                                            <div class="profile-widget">
                                                <div class="profile-img">
                                                    <a href="#">
                                                        <img src="<?php echo e(asset('assets/img/pdf-icon.png')); ?>" alt=""></a>
                                                </div>
                                                <div class="dropdown profile-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item edit_doc_btn" href="#" data-bs-toggle="modal" data-bs-target="#add_document" data-id="<?php echo e($val->id); ?>"> <i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item deletemaindocButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_maindoc" data-id="<?php echo e($val->id); ?>"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>
                                                    </div>
                                                </div>
                                                <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="<?php echo e(route('company.detail',$val->id)); ?>"><?php echo e(ucfirst($val->reg_name)); ?></a>
                                                </h4>
                                            
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <div class="col-12 py-5 text-center">No data found</div>
                                    <?php endif; ?>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>    
                    
                </div>
            </div>
            <!-- /Projects Tab -->
           
        </div>   

        <!-- /Page Content -->
        <div id="add_company" class="modal custom-modal fade " role="dialog">
            <?php echo $__env->make('settings/company_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <!-- /Page Content -->
        <div id="add_document" class="modal custom-modal fade " role="dialog">
            <?php echo $__env->make('settings/document_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <!-- Delete Company Modal -->
        <div class="modal custom-modal fade" id="delete_maindoc" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Document</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                <form action="<?php echo e(route('document.delete')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="document_id" id="doc_delete_id">
                                            <button type="submit" class="btn btn-primary btn-large continue-btn" style="width: 100%;">Delete</button>
                                </form>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Company Modal -->

    </div>
    <!-- /Page Content -->

    
</div>
<!-- /Page Wrapper -->

</body>


</html>

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    $(document).on('click','.editButton',function(){
        $('#add_company').html('');
        var id= $(this).attr('data-id');
        $.ajax({
        url: "<?php echo e(route('getcompanyDetailsById')); ?>",
        type: "POST",
        dataType: "json",
        data: {"_token": "<?php echo e(csrf_token()); ?>", id:id},
        success:function(response)
            {
                $('#add_company').html(response.html).fadeIn();
            }
        });
    });

    $('#add_company').on('hidden.bs.modal', function () {
        $("input[type=text], textarea").val("");
        $('#img1').remove();
        $('.company_id_hid').val('');
        $('.leave_m_title').text('Add Company');
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

    $("#document_form").validate({
        rules: {
            reg_name: {
                required : true
            },
            reg_no: {
                required : true
            },
            civil_no: {
                required : true
            },
            issuing_date: {
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
            reg_name: {
                required : "Please enter registration name"
            },
            reg_no: {
                required : "Please enter registration number"
            },
            civil_no: {
                required : "Please enter civil number"
            },
            issuing_date: {
                required : "Please select issuing date"
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

    $(document).on('click','.edit_doc_btn',function(){
        $('#add_document').html('');
        var id= $(this).attr('data-id');
        $.ajax({
        url: "<?php echo e(route('getdocumentDetailsById')); ?>",
        type: "POST",
        dataType: "json",
        data: {"_token": "<?php echo e(csrf_token()); ?>", id:id},
        success:function(response)
            {
                $('#add_document').html(response.html).fadeIn();
            }
        });
    });

    $('#add_document').on('hidden.bs.modal', function () {
        $("input[type=text] , input[type=date], textarea").val("");
        $('#img1').remove();
        $('.doc_id_hid').val('');
        $('.leave_m_title').text('Add Document');
        $('.doc_table').remove();
    });


    $(document).on('click','.deleteDocButton',function(){
        var id = $(this).data('data');
        $.ajax({
        url: "<?php echo e(route('delete_company_document')); ?>",
        type: "POST",
        dataType: "json",
        data: {"_token": "<?php echo e(csrf_token()); ?>", id:id},
        success:function(response)
            {
                $('.doc_'+response.id).remove();
            }
        });
    });

    $(document).on('click','.deletemaindocButton',function(){
        var id = $(this).attr('data-id');
        $('#doc_delete_id').val(id);
    });
</script>
<?php /**PATH C:\wamp64_new\www\hrm\resources\views/settings/company_detail.blade.php ENDPATH**/ ?>