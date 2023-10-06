@include('includes/header')
@include('includes/sidebar')
   <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid">
        @include('flash-message')          
            <!-- Page Header -->
            @include('includes/breadcrumbs', ['title' => $title])
            
            <!-- /Page Header -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="">
                        <table class="table table-striped custom-table mb-0 datatablex" id="dt_table">
                            <thead>
                                <tr>
                                    <th style="width: 30px;">#</th>
                                    <th>Department</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
        <!-- Add Department Modal -->
        <div id="add_Form" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Department</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form  action="/departmentInsert" method="post" id="addForm">
                            @csrf
                            <div class="form-group">
                                <label>Department Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="department" >
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Department Modal -->
        
        <!-- Edit Department Modal -->
        <div id="edit_department" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Department</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/departmentUpdate" method="post" id="editForm">
                            @csrf
                            <div class="form-group">
                                <label>Department Name <span class="text-danger">*</span></label>
                                <input class="form-control" value="" id="department_name" name="department_name" type="text">
                                <input class="form-control" value="" id="department_id" name="department_id" type="hidden">
                           
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Department Modal -->

        <!-- Delete Department Modal -->
        <div class="modal custom-modal fade" id="delete_department" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Department</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <form action="/departmentDelete" method="post">
                                        @csrf
                                        <input type="hidden" name="department_delete_id" id="department_delete_id" value="">
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
        <!-- /Delete Department Modal -->
        
    </div>
    <!-- /Page Wrapper -->


</div>
<!-- end main wrapper-->

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
                url: "{{ route('department') }}",
            },

            columns: [
                {
                    "render": function() {
                        return i++;
                    }
                },
                {
                    data: 'department',
                    name: 'department',

                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
        });
    });
</script>
<script>
    $(document).on('click','.editButton',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            console.log(key);
            $('#department_'+key).val(value);
        });
    })
</script>


<script>
    $(document).on('click','.deleteButton',function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#department_delete_'+key).val(value);
        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addForm").validate({
            rules: {
                department: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "{{ route('isDepartmentExists') }}",
                        data :{
                            "_token": "{{ csrf_token() }}",
                        }
                    }
                }
            },
            messages: {
                department: {
                    required : 'Department name is required',
                    remote: 'Department already exists',
                }
            },
       });
       
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        
        $("#editForm").validate({
            rules: {
                department_name: {
                    required : true,
                    remote: { 
                        type: 'post',
                        url: "{{ route('isDepartmentExists') }}",
                        data: {
                            'id': function () {
                                return $('#department_id').val();
                            },
                            "_token": "{{ csrf_token() }}",
                        },
                    }
                }
            },
            messages: {
                department_name: {
                    required : 'Department name is required',
                    remote: 'Department already exists',
                }
            },
       });
       
    });
</script>