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
                    <h3 class="page-title">Company</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Company</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_company"><i class="fa fa-plus"></i> Add Company</a>
                </div>
            </div>
        </div>           
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="/company-settings">
                    @csrf
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" name="company_name" value="{{$search['company_name'] ?? ''}}">
                        <label class="focus-label">Company Name</label>
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
            @if(isset($residency_list) && count($residency_list) > 0)
                @foreach ($residency_list as $val)
                <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3 m-ht-3">
                    <div class="profile-widget">
                        <div class="profile-img">
                            <a href="{{route('company.detail',$val->id)}}" class="avatar">
                                <img src="{{ ($val->logo!=null)?'uploads/profile/'.$val->logo:'assets/img/profiles/avatar.png'}}" alt=""></a>
                        </div>
                        <div class="dropdown profile-action">
                            <a href="{{route('company.detail',$val->id)}}" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{route('company.detail',$val->id)}}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_company" data-id="{{$val->id}}"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>
                            </div>
                        </div>
                        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="{{route('company.detail',$val->id)}}">{{ ucfirst($val->name) }}</a>
                        </h4>
                      
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 py-5 text-center">No company found</div>
            @endif
        </div>

    <!-- /Page Content -->
    <div id="add_company" class="modal custom-modal fade " role="dialog">
        @include('settings/company_modal')
    </div>
    <!-- Delete Company Modal -->
    <div class="modal custom-modal fade" id="delete_company" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Company</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                            <form action="{{route('company.delete')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="residency_id" id="residency_delete_id">
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
    $(document).ready(function () {
        var i = 1;

        var table_table = $('#dt_table').DataTable({
            responsive: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('company-settings') }}",
            },

            columns: [
                {
                    "render": function() {
                        return i++;
                    }
                },
                {
                    data: 'name',
                    name: 'name',

                },
                {
                    data: 'city',
                    name: 'city',

                },
                {
                    data: 'email',
                    name: 'email',

                },
                {
                    data: 'phone',
                    name: 'phone',

                },
                {
                    data: 'website',
                    name: 'website',

                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
        });


        $("#company_form").validate({
            rules: {
                name: {
                    required : true},
            },
            messages: {
                name: {
                    required : 'Please enter company name',
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
    });
</script>

<script>

    $('#add_company').on('hidden.bs.modal', function () {
        $("input[type=text], textarea").val("");
        $('#img1').remove();
        $('.company_id_hid').val('');
        $('.leave_m_title').text('Add Company');
    });
</script>


<script>
    $(document).on('click','.deleteButton',function(){
        var id = $(this).attr('data-id');
        $('#residency_delete_id').val(id);
    });
</script>
