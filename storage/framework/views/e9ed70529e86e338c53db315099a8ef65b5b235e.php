<head> 
    <script type="text/javascript" src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
        <script>$(document).ready(function() {

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
            element.find('.dept_select').val('');
            element.find('.title_select').val('');
            element.removeClass('d-none');
            element.find('.dept_select').addClass('select');
            element.find('.title_select').addClass('select');
            var j = $('.add_dept_div').not('.d-none').length;

            element.insertAfter($(this).parents().find('.add_dept_div:last'));
            $('.select').select2({
                //-^^^^^^^^--- update here
                minimumResultsForSearch: 1,
                //allowClear: true,
                width: '100%',
                dropdownParent: $(this).parent()
            });
            
            if(j>=1){
                //$('.dept_select:last').select2('destroy');
                $('.dept_select:last').attr('id','dept_select'+j);
                $('.dept_select:last').attr('name','sub_department[]');
                $('#dept_select'+j).select2('destroy');
                $('#dept_select'+j).select2();
                $('.title_select:last').attr('id','title_select'+j);
                $('.title_select:last').attr('name','sub_title[]');
                $('#title_select'+j).select2('destroy');
                $('#title_select'+j).select2();
                $('.select').select2({
                //-^^^^^^^^--- update here
                minimumResultsForSearch: 1,
                //allowClear: true,
                width: '100%',
                dropdownParent: $(this).parent()
            });
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

        $(document).on('change', '.title_select', function() {

            // for department hide/show
            var prio = $(this).find(":selected").data("priority");
            $(this).find('.department_div').show();
            if(prio == '1' || prio == '2')
            {
                $(this).find('.department_div').hide();
                $(this).find('.dep_hid').val(1);
                $(this).append('<select class="select dept_select" name="sub_department[]"></select>');
            }

            //for multi user check
        });

        $("#add_leave").on("hidden.bs.modal", function(){
        
            $(".select").val(null).trigger("change");
            $('.leave_id').val('');
            $('.add_dept_div').slice(1).remove();
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
       
    });</script></head>
                    <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Selling Period</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('selling_period.store')); ?>" method="post" id="addForm">
                    <?php echo csrf_field(); ?>
                        <input type="hidden" name="company_id" id="company_id">
                        <input type="hidden" name="branch_id" id="branch_id">
                        <div class="form-group">
                            <label>Item Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="item_name" id="item_name">
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_bill_count" id="is_bill_count" value="<?php echo e($user->is_bill_count ?? 1); ?>" <?php if(isset($user->is_bill_count) && $user->is_bill_count==1): ?> checked <?php endif; ?>>
                                <label class="form-check-label" for="is_bill_count">
                                    Bill Count
                                </label>

                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/policies/selling_period_modal.blade.php ENDPATH**/ ?>