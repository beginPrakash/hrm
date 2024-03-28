<head> 
    <script type="text/javascript">
        $(document).ready(function() {
            $("#addForm").validate({
                rules: {
                    item_name: {
                        required : true,
                    },
                },
                messages: {
                    item_name: {
                        required : 'Item name is required',
                    },           
                },
        });
        
        });

        $("#add_Form").on("hidden.bs.modal", function(){
        
            $("#is_bill_count").prop('checked',false);
            $('#item_name').val('');
            $('.leave_m_title').text('Create Leave');
        });
    </script>
</head>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title">{{(isset($data) && !empty($data->id)) ? 'Change' : 'Create'}} Leave</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{route('selling_period.store')}}" method="post" id="addForm">
            @csrf
                <div class="row">
                    <input type="hidden" name="selling_id" id="selling_id" value="{{$data->id ?? ''}}">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Leave Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="leave_name" id="leave_name" value="{{$data->leave_name ?? ''}}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Leave Code <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="leave_code" id="leave_code" value="{{$data->leave_code ?? ''}}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <span>Show leave to user</span><input class="form-check-input" type="checkbox" role="switch" data-url="{{route('settings.changeanalytic_status')}}" id="analytic_btn" {{(!empty($get_analytics)) ? 'checked' : ''}}>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div><label>Salary</label></div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_bill_count" id="is_bill_count" value="1" @if(isset($data->is_bill_count) && $data->is_bill_count==1) checked @endif>
                                <label class="form-check-label" for="is_bill_count">
                                    Pay Salary
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_bill_count" id="is_bill_count" value="1" @if(isset($data->is_bill_count) && $data->is_bill_count==1) checked @endif>
                                <label class="form-check-label" for="is_bill_count">
                                    Pay allowance
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-control" type="text" name="sal_per" id="sal_per" value="{{$data->sal_per ?? ''}}">
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-control" type="text" name="allowe_per" id="allowe_per" value="{{$data->allowe_per ?? ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div><label>Benefits</label></div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_bill_count" id="is_bill_count" value="1" @if(isset($data->is_bill_count) && $data->is_bill_count==1) checked @endif>
                                <label class="form-check-label" for="is_bill_count">
                                    Pay Salary
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_bill_count" id="is_bill_count" value="1" @if(isset($data->is_bill_count) && $data->is_bill_count==1) checked @endif>
                                <label class="form-check-label" for="is_bill_count">
                                    Pay allowance
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>