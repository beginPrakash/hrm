@include('includes/header')
@include('includes/sidebar')
   <!-- Page Wrapper -->
<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
    @include('flash-message')  
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Transportation</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Transportation</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_transp"><i class="fa fa-plus"></i> Add Transportation</a>
                </div>
            </div>
        </div>           
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="/transportation">
                    @csrf
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" name="car_name" value="{{$search['car_name'] ?? ''}}">
                        <label class="focus-label">Car Name</label>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-3">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success w-100"> Search </button>  
                    </div>  
                </div>
            </div>
        </form>
        <!-- Search Filter -->
        <div class="row staff-grid-row pt-4">
            @if(isset($transp_list) && count($transp_list) > 0)
                @foreach ($transp_list as $val)
                <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3 m-ht-3">
                    <div class="profile-widget">
                        <div class="profile-img">
                            <a href="{{route('transportation.detail',$val->id)}}"><img src="{{asset('assets/img/pdf-icon.png')}}" alt=""></a>
                        </div>
                        <div class="dropdown profile-action">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item edit_trans_btn" href="{{route('transportation.detail',$val->id)}}"> <i class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_transpo" data-id="{{$val->id}}"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>
                            </div>
                        </div>
                        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="{{route('transportation.detail',$val->id)}}">{{ ucfirst($val->car_name) }}</a>
                        </h4>
                      
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 py-5 text-center">No transportation found</div>
            @endif
        </div>

    <!-- /Page Content -->
    <div id="add_transp" class="modal custom-modal fade" role="dialog">
        @include('transportation/transportation_modal')
    </div>
    <!-- Delete Company Modal -->
    <div class="modal custom-modal fade" id="delete_transpo" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Transportation</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                            <form action="{{route('transportation.delete')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="transp_id" id="transp_delete_id">
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
<!-- /Page Wrapper -->


</div>


</body>


</html>

@include('includes/footer')

<script type="text/javascript">

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

    

    $('.digitsOnly').keypress(function(event){
        if(event.which !=8 && isNaN(String.fromCharCode(event.which))){
            event.preventDefault();
        }
    });

    $('#add_transp').on('hidden.bs.modal', function () {
        $("input[type=text], textarea").val("");
        $('.company_id_hid').val('');
        $('.doc_table').remove();
        $('.select').val('').trigger('change');
        $('.leave_m_title').text('Add Transportation');
    });

    $("#trans_form").validate({
        rules: {
            car_name: {
                required : true
            },
            colour: {
                required : true
            },
            model: {
                required : true
            },
            license_number: {
                required : true
            },
            license_expiry: {
                required : true
            },
            alert_days: {
                required : true
            },
            under_company: {
                required : true
            },
            under_subcompany: {
                required : true
            },
            cost: {
                required : true
            },
        },
        messages: {
            car_name: {
                required : 'Please enter car name',
            },
            colour: {
                required : 'Please enter colour',
            },
            model: {
                required : 'Please enter model',
            },
            license_number: {
                required : 'Please enter license number',
            },
            license_expiry: {
                required : 'Please select license expiry',
            },
            alert_days: {
                required : 'Please enter alert days',
            },
            under_company: {
                required : 'Please select company',
            },
            under_subcompany: {
                required : 'Please select subcompany',
            },
            cost: {
                required : 'Please enter cost',
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

</script>


<script>
    $(document).on('click','.deleteButton',function(){
        var id = $(this).attr('data-id');
        $('#transp_delete_id').val(id);
    });
</script>
