<script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
<script>
    var reg_url = "{{route('getRegtype')}}";
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
        url: "{{route('deleteregtypebydocument')}}",
        type: "POST",
        dataType: "json",
        data: {"_token": "{{ csrf_token() }}", doc_id:doc_id,reg_id:reg_id},
        success:function(response)
            {
                $('#add_document').html(response.html).fadeIn();
            }
        });
    });

    var reghtml = '{{$reg_html ?? ''}}';
    //$('#reg_type').parent('.tokenfield').prepend($('.regtype_data').text());

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
            branch_id:{
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
            branch_id:{
                required : "Please select branch"
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
            <h5 class="modal-title leave_m_title">{{(isset($doc_data->id) && !empty($doc_data->id)) ? 'Edit' : 'Add'}} Document</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <form action="{{route('document.store')}}" method="POST" enctype="multipart/form-data" id="document_form">
            <input type="hidden" name="id" value="{{$doc_data->id ?? ''}}" class="doc_id_hid">
            @if(isset($doc_data) && !empty($doc_data))
                <input type="hidden" name="company_id" value="{{$doc_data->company_id ?? ''}}">
                <div class="regtype_data d-none">
                    {{$reg_html ?? ''}}
                </div>
            @else
                <input type="hidden" name="company_id" value="{{$company_detail->id ?? ''}}">
            @endif
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Registration Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="reg_name" value="{{$doc_data->reg_name ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Registration Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="reg_no" value="{{$doc_data->reg_no ?? ''}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Registration Type</label>
                        <div class="input-group regtype_main_div">
                            <input type="text" id="reg_type" name="reg_type" placeholder="" autocomplete="off" class="form-control input-lg" value="{{(isset($doc_data->regis_type) && !empty($doc_data->regis_type)) ? $doc_data->regis_type->name : '' }}" />
                        </div>
                        <br />
                        <span id="country_name"></span>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Civil Number <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="civil_no" value="{{$doc_data->civil_no ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Issuing Date<span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="issuing_date" value="{{$doc_data->issuing_date ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Expiry Date<span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="expiry_date" value="{{$doc_data->expiry_date ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Alert Days<span class="text-danger">*</span></label>
                        <input class="form-control digitsOnly" type="text" name="alert_days" value="{{$doc_data->alert_days ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input class="form-control" type="text" name="remarks" value="{{$doc_data->remarks ?? ''}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Select Branch <span class="text-danger">*</span></label>
                        <select class="form-control select" name="branch_id">
                            <option value="">Select Branch</option>
                            @if(isset($branches) && count($branches) > 0)
                                @foreach($branches as $key => $val)
                                    <option value="{{$val->id}}" {{(isset($doc_data) && !empty($doc_data->branch_id) && ($doc_data->branch_id == $val->id)) ? 'selected' : ''}}>{{$val->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Cost<span class="text-danger">*</span></label>
                        <input class="form-control allowfloatnumber" type="text" name="cost" value="{{$doc_data->cost ?? ''}}">
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
                @if(isset($doc_files) && count($doc_files) > 0)
                    <table class="table doc_table">
                        <tr>
                            <th>Title</th>
                            <th>File</th>
                            <th class="text-end">Action</th>
                        </tr>
                        @foreach($doc_files as $key => $val)
                            <tr class="doc_{{$val->id}}">
                                <td>
                                    <small class="block text-ellipsis">
                                        <span class="text-muted">Uploaded on : {{dateDisplayFormat($val->created_at)}}</span>
                                    </small>
                                </td>
                                <td>
                                    <a href="{{asset('uploads/company_documents/'.$val->doc_file)}}" class="text-info" target="_blank"><i class="fa fa-file"></i><?php //echo $edoc->document_file; ?></a>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a class="dropdown-item deleteDocButton" data-data="{{$val->id}}"><i class="fa fa-trash-o m-r-5 text-danger"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
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
</div>