<script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
<script>
    $("#addEditForm").validate({
        rules: {
            title: {
                required : true
            },
        },    
        messages: {
            title: {
                required : 'Role Name is required',
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


</script>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title">{{(isset($rolesData->id) && !empty($rolesData->id)) ? 'Edit' : 'Add'}} Role</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form  action="{{route('roles.store')}}" method="post" id="addEditForm">
                @csrf
                <input type="hidden" name="id" class="role_id" value="{{$rolesData->id ?? ''}}">
                <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" id="title" value="{{$rolesData->title ?? ''}}">
                </div>
               <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit" id="addBonusBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>