<script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>

<script>
    $("#addEditForm").validate({
        rules: {
            role_id: {
                required : true
            },
            employee_id: {
                required : true
            },
        },    
        messages: {
            role_id: {
                required : 'Role is required',
            },
            employee_id: {
                required : 'Employee is required',
            },
        },
        errorPlacement: function (error, element) {
            if (element.prop("type") == "text" || element.prop("type") == "number" || element.prop("type") == "textarea") {
                error.insertAfter(element);
            } else {
                error.insertAfter(element.parent());
            }
        },
    });

    $(document).on('click','.company_check',function(){
        $('.branch_menu').remove();
        var sel_val = $('.company_check:checked').map(function() {
            return this.value;
        }).get().join(',');

        $.ajax({
            url: "{{route('sales_target.branchlistbycompany')}}",
            type: "POST",
            dataType: "json",
            data: {"_token": "{{ csrf_token() }}", sel_val:sel_val},
            success:function(response)
                {
                    $('.branch_checklist').after(response.html).fadeIn();
                }
        });
    });
    $('.selectwith_search').select2({
		minimumResultsForSearch: 1,
		width: '100%'
	});


</script>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title">{{(isset($rolesData->id) && !empty($rolesData->id)) ? 'Edit' : 'Add'}} User Role</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form  action="{{route('user_roles.store')}}" method="post" id="addEditForm">
                @csrf
                <input type="hidden" name="id" class="role_id" value="{{$rolesData->id ?? ''}}">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Roles <span class="text-danger">*</span></label>
                            <select class="select editsched" name="role_id" id="role_id">
                                <option value="">Select Roles</option>
                                @if(isset($role_list) && count($role_list) > 0)  
                                    @foreach($role_list  as $key => $val)
                                        <option value="{{$key}}" {{(isset($rolesData->role_id) && ($key == $rolesData->role_id)) ? 'selected' : ''}}>{{$val}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Employee<span class="text-danger">*</span></label>
                            <select class="selectwith_search editsched" name="emp_id">
                                <option value="">Select Employee Profile</option>
                                @if(isset($emp_list) && count($emp_list) > 0)  
                                    @foreach($emp_list  as $key => $val)
                                        <option value="{{$val->id}}" {{(isset($rolesData->employee_id) && ($val->id == $rolesData->employee_id)) ? 'selected' : ''}}>{{$val->first_name}} {{$val->last_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6"> 
                        <div class="form-group form-focus select-focus">
                            <label class="col-form-label">Select Company <span class="text-danger">*</span></label>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" 
                                        id="dropdownMenu1" data-toggle="dropdown" 
                                        aria-haspopup="true" aria-expanded="true">
                                    Select Company
                                
                                </button>
                                <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
                                    @if(isset($company_list) && count($company_list) > 0)  
                                        @foreach($company_list  as $key => $val)
                                            @php $selected = ''; @endphp
                                            @if(isset($rolesData) && !empty($rolesData))
                                            @php $com_data = _get_company_name_by_uroles($rolesData->id); @endphp 
                                            @if(!empty($com_data))
                                                    @php
                                                    if (in_array($key, $com_data)) { 
                                                        $selected = 'checked';
                                                    } else { 
                                                        $selected = '';
                                                    } 
                                                    @endphp
                                                @endif
                                            @endif
                                            <li>
                                                <label>
                                                    <input type="checkbox" class="company_check select_change" name="company[]" value="{{$key}}" {{$selected}}>{{$val}}
                                                </label>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div> 
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6"> 
                        <div class="form-group form-focus select-focus">
                            <label class="col-form-label">Select Branch <span class="text-danger">*</span></label>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle branch_checklist" type="button" 
                                        id="dropdownMenu1" data-toggle="dropdown" 
                                        aria-haspopup="true" aria-expanded="true">
                                    Select Branch
                                </button>
                                
                                @if(isset($rolesData) && !empty($rolesData))
                                <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
                                    @if(isset($branch_data) && count($branch_data) > 0) 
                                        @foreach($branch_data  as $key => $val)
                                        
                                            @php $selected = ''; @endphp
                                            
                                            @php $br_data = _get_branch_by_uroles($rolesData->id); @endphp 
                                            @if(!empty($br_data))
                                                    @php
                                                    if (in_array($val->id, $br_data)) { 
                                                        $selected = 'checked';
                                                    } else { 
                                                        $selected = '';
                                                    } 
                                                    @endphp
                                                @endif
                                            
                                            <li>
                                                <label>
                                                    <input type="checkbox" class="select_change" name="brnach_list[]" value="{{$val->id}}" {{$selected}}>{{$val->name}}
                                                </label>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                @endif
                            </div> 
                        </div>
                    </div>
                </div>
               <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit" id="addBonusBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>