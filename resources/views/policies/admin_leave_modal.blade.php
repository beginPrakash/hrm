<head> 
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
        <script>$(document).ready(function() {
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
                minimumResultsForSearch: -1,
                //allowClear: true,
                width: '100%'
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
                                <h5 class="modal-title leave_m_title">{{(isset($leaveData->id) && !empty($leaveData->id)) ? 'Edit' : 'Add'}} Leave Hierarchy</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form  action="{{route('admin_leaves.store')}}" method="post" id="admin_leaves_form">
                                    @csrf
                                    <input type="hidden" name="id" class="leave_id" value="{{$leaveData->id ?? ''}}">
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select" name="leave_type" id="leave_type">

                                            <option value="">Select Leave Type</option>
                                             <?php foreach ($leavetype as $value) {?>

                                            <option value="<?php echo $value->id?>" {{(isset($leaveData->leave_type) && !empty($leaveData->leave_type) && ($leaveData->leave_type == $value->id)) ? 'selected' : ''}}><?php echo $value->name?></option>
                                        <?php }?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Title<span class="text-danger">*</span></label>
                                                <select class="select main_title" id="main_title" name="main_title">
                                                    <option value="">Select Title</option>
                                                    @if(isset($designations) && count($designations) > 0)
                                                        @foreach($designations as $key => $val)
                                                            <option value="{{$val->id}}" data-priority="{{$val->priority_level}}" {{(isset($leaveData->main_desig_id) && !empty($leaveData->main_desig_id) && ($leaveData->main_desig_id == $val->id)) ? 'selected' : ''}}>{{$val->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        @if(isset($leaveData->id) && !empty($leaveData->id))
                                            @if(isset($leaveData->main_dept_id) && !empty($leaveData->main_dept_id))
                                                <div class="col-sm-6">
                                                    <div class="form-group main_department">
                                                        <label>Department <span class="text-danger">*</span></label>
                                                        <select class="select shift_addschedule addsched" id="main_department" name="main_department">
                                                            <option value="">Select Department</option>
                                                            @if(isset($departments) && count($departments) > 0)
                                                                @foreach($departments as $key => $val)
                                                                    <option value="{{$val->id}}" {{(isset($leaveData->main_dept_id) && !empty($leaveData->main_dept_id) && ($leaveData->main_dept_id == $val->id)) ? 'selected' : ''}}>{{$val->name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <input type="hidden" name="main_dep_hid" value="0" class="main_dep_hid">
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-sm-6">
                                                    <div class="form-group main_department" style="display:none">
                                                        <label>Department <span class="text-danger">*</span></label>
                                                        <select class="select shift_addschedule addsched" id="main_department" name="main_department">
                                                            <option value="">Select Department</option>
                                                            @if(isset($departments) && count($departments) > 0)
                                                                @foreach($departments as $key => $val)
                                                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <input type="hidden" name="main_dep_hid" value="0" class="main_dep_hid">
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="col-sm-6">
                                                    <div class="form-group main_department">
                                                        <label>Department <span class="text-danger">*</span></label>
                                                        <select class="select shift_addschedule addsched" id="main_department" name="main_department">
                                                            <option value="">Select Department</option>
                                                            @if(isset($departments) && count($departments) > 0)
                                                                @foreach($departments as $key => $val)
                                                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <input type="hidden" name="main_dep_hid" value="0" class="main_dep_hid">
                                                    </div>
                                                </div>
                                        @endif
                                        
                                    </div>
                                    <div class="form-group">
                                        <label>Select Approver Title and Department</label>
                                    </div>
                                    @if(isset($leaveData) && !empty($leaveData))
                                        @php $decode_data = json_decode($leaveData->leave_hierarchy); @endphp
                                      
                                        @if(!empty($decode_data))
                                            @foreach($decode_data as $dkey => $dval)
                                                <div class="row add_dept_div">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <select class="select title_select" name="sub_title[]">
                                                                <option value="">Select Title</option>
                                                                @if(isset($designations) && count($designations) > 0)
                                                                    @foreach($designations as $key => $val)
                                                                        <option value="{{$val->id}}" data-priority="{{$val->priority_level}}" {{($dval->desig == $val->id) ? 'selected' : ''}}>{{$val->name}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if(isset($dval->dept) && $dval->dept != null)
                                                    <div class="col-md-5 department_div">
                                                        <div class="form-group">
                                                            <select class="select dept_select" name="sub_department[]">
                                                                <option value="">Select Department</option>
                                                                @if(isset($departments) && count($departments) > 0)
                                                                    @foreach($departments as $key => $val)
                                                                        <option value="{{$val->id}}" {{($dval->dept == $val->id) ? 'selected' : ''}}>{{$val->name}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" name="dep_hid" value="0" class="dep_hid">
                                                        </div>
                                                    </div>
                                                    @else
                                                    <input type="hidden" class="dept_select" name="sub_department[]">
                                                    @endif
                                                    <div class="col-md-2 add_btn_div">
                                                        @if($dkey == 0)
                                                            <button type="button" class="btn btn-success {{(isset($leaveData->id) && !empty($leaveData->id)) ? 'add_more_dept_btn' : 'add_more_dept_btn'}}"><i class="fa fa-plus"></i></button>
                                                        @else
                                                            <button type="button" class="btn btn-primary plus-minus {{(isset($leaveData->id) && !empty($leaveData->id)) ? 'remove_dept_btn' : 'remove_dept_btn'}}"><i class="fa fa-minus"></i></button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @else
                                    <div class="row add_dept_div">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <select class="select title_select" name="sub_title[]">
                                                    <option value="">Select Title</option>
                                                    @if(isset($designations) && count($designations) > 0)
                                                        @foreach($designations as $key => $val)
                                                            <option value="{{$val->id}}" data-priority="{{$val->priority_level}}">{{$val->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5 department_div">
                                            <div class="form-group">
                                                <select class="select dept_select" name="sub_department[]">
                                                    <option value="">Select Department</option>
                                                    @if(isset($departments) && count($departments) > 0)
                                                        @foreach($departments as $key => $val)
                                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" name="dep_hid" value="0" class="dep_hid">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 add_btn_div">
                                            <button type="button" class="btn btn-success add_more_dept_btn"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    
                                    @endif
                                    <div class="row add_dept_div add_new_dept d-none">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <select class="title_select">
                                                    <option value="">Select Title</option>
                                                    @if(isset($designations) && count($designations) > 0)
                                                        @foreach($designations as $key => $val)
                                                            <option value="{{$val->id}}" data-priority="{{$val->priority_level}}">{{$val->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5 department_div">
                                            <div class="form-group">
                                                <select class="dept_select">
                                                    <option value="">Select Department</option>
                                                    @if(isset($departments) && count($departments) > 0)
                                                        @foreach($departments as $key => $val)
                                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="hidden" name="dep_hid" value="0" class="dep_hid">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 add_btn_div">
                                            <button type="button" class="btn btn-success add_more_dept_btn"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" type="submit" id="addLeaveBtn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>