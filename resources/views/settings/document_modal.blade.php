<script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
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
            <h5 class="modal-title leave_m_title">{{(isset($residency->id) && !empty($residency->id)) ? 'Edit' : 'Add'}} Document</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <form action="{{route('document.store')}}" method="POST" enctype="multipart/form-data" id="document_form">
            <input type="hidden" name="id" value="{{$residency->id ?? ''}}" class="doc_id_hid">
            <input type="hidden" name="company_id" value="{{$company_detail->id ?? ''}}">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Registration Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="reg_name" value="{{$residency->reg_name ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Registration Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="reg_no" value="{{$residency->reg_no ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Civil Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="civil_no" value="{{$residency->civil_no ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Issuing Date<span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="issuing_date" value="{{$residency->issuing_date ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Expiry Date<span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="expiry_date" value="{{$residency->expiry_date ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Alert Days<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="alert_days" value="{{$residency->alert_days ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input class="form-control" type="text" name="remarks" value="{{$residency->remarks ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Cost<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="cost" value="{{$residency->cost ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Upload File</label>
                        <div class="image-upload">
                            <label for="file-input4">
                                <img src="<?php echo (isset($residency) && $residency->logo!=NULL)?'../uploads/logo/'.$residency->logo:""; ?>" id="img1"/>
                            </label>
                            <input id="file-input1" name="doc_file[]" type="file" onchange="previewFile(this, 'img1');"/>
                        </div>
                    </div>
                </div>
                <div id="div_doc_addmore"></div> 

                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm btn-success pull-right" id="doc_addmore">Add More</button>
                    </div>
                </div> 
            </div>
            <div class="submit-section">
                <button type="submit" class="btn btn-primary submit-btn">Update</button>
            </div>
        </form> 
        </div>
    </div>
</div>