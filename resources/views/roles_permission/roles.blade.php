@include('includes/header')
@include('includes/sidebar')


 <!-- Page Wrapper -->
            <div class="page-wrapper">

                <!-- Page Content -->
                <div class="content container-fluid">
                @include('flash-message') 
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Roles</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Roles</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_leave"><i class="fa fa-plus"></i> Add Roles</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class=" table table-bordered table-striped table-hover datatable datatable-LoanApplication">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;">#ID</th>
                                            <th>Name</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($roles_data))
                                        {
                                            $i = 0;
                                            foreach($roles_data as $data)
                                            {
                                                $i++;
                                                
                                            ?>
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$data->title}}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_leave" data-id="{{$data->id}}" class="action-icon edit_hierarchy"><i class="fa fa-pencil"></i></a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#delete_roles" data-id="{{$data->id}}" class="delete_roles"><i class="fa fa-trash-o m-r-5" ></i></a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Page Content -->
                
                <!-- Add Role Modal -->
                <div id="add_leave" class="modal custom-modal fade modal_div" role="dialog">
                    @include('roles_permission/roles_modal')
                </div>
                <!-- /Add Role Modal -->

                <!-- Delete Role Modal -->
                <div class="modal custom-modal fade" id="delete_roles" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Delete Roles</h3>
                                    <p>Are you sure want to delete?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <div class="row">
                                        <div class="col-6">
                                            <form action="{{route('roles.delete')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="roles_id" id="roles_id" value="">
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
                <!-- /Delete Role Modal -->
   
            </div>
            <!-- /Page Wrapper -->


@include('includes/footer')

<script type="text/javascript">
    $(document).ready(function() {
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
    });

    $(document).on('click','.edit_hierarchy',function(){
        $('#add_leave').html('');
        var id= $(this).attr('data-id');
        $.ajax({
        url: "{{route('roles_details')}}",
        type: "POST",
        dataType: "json",
        data: {"_token": "{{ csrf_token() }}", id:id},
        success:function(response)
            {
                $('#add_leave').html(response.html).fadeIn();
            }
        });
    });

    $(document).on('click','.delete_roles',function(){
        var id= $(this).attr('data-id');
        $('#roles_id').val(id);
    });

       $('#add_leave').on('hidden.bs.modal', function () {
            $('#title').val('');
            $('.role_id').val('');
            $('.leave_m_title').text('Add Roles');
        });
       

    
</script>
