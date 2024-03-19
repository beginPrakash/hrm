<script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
<script>
    $('.user_select').select2({
        minimumResultsForSearch: 4,
        width: '100%',
        //allowClear: true,
        dropdownParent: $(".modal_div"),
    });
    $("#addEditForm").validate({
        rules: {
            employee_id: {
                required : true},
            ot_date:  {
                required : true},
            ot_hours:  {
                required : true},
            description:  {
                required : true
            },
        },    
        messages: {
            employee_id: {
                required : 'Employee is required',
            },
            ot_date: {
                required : 'OverTime Date is required',
            },
            ot_hours: {
                required : 'OverTime hours is required',
            },
            description: {
                required : 'Description is required',
            }
        },
        errorPlacement: function (error, element) {
            if (element.prop("type") == "text" || element.prop("type") == "number" || element.prop("type") == "textarea") {
                error.insertAfter(element);
            } else {
                error.insertAfter(element.parent());
            }
        },
    });


</script>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title">{{(isset($otData->id) && !empty($otData->id)) ? 'Edit' : 'Add'}} OverTime</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form  action="{{route('master_ot.store')}}" method="post" id="addEditForm">
                @csrf
                <input type="hidden" name="id" class="leave_id" value="{{$otData->id ?? ''}}">
                <div class="form-group">
                    <label>Select Employee<span class="text-danger">*</span></label>
                    <select class="user_select" name="employee_id" id="employee_id">
                        <option value="">Select Employee</option>
                        @if(isset($userdetails) && count($userdetails) > 0)
                            @foreach($userdetails as $key => $val)
                                <option value="{{$val->id}}" {{(isset($otData->employee_id) && ($val->id == $otData->employee_id)) ? 'selected' : ''}}>{{$val->first_name}} {{$val->last_name ?? ''}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label>Overtime Date <span class="text-danger">*</span></label>
                    <div class="cal-iconx">
                        <input class="form-control datetimepicker_fromx" type="date" name="ot_date" min="" value="{{$otData->ot_date ?? ''}}" id="ot_date">
                    </div>
                </div>
                <div class="form-group">
                    <label>Overtime Hours <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="ot_hours" id="ot_hours" min="1" value="{{$otData->ot_hours ?? ''}}">
                </div>
                <div class="form-group">
                    <label>Description <span class="text-danger">*</span></label>
                    <textarea rows="4" class="form-control" name="description" id="description">{{$otData->description ?? ''}}</textarea>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit" id="addBonusBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>