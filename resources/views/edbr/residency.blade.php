@include('includes/header')
@include('includes/sidebar')
   <!-- Page Wrapper -->
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
                                <th><?php echo ucfirst($title); ?> </th>
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

    <!-- Add Company Modal -->
    <div id="add_Form" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Company</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/residencyInsert" method="post" id="addForm">
                        @csrf
                        <div class="form-group">
                            <label>Company Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="residency">
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Company Modal -->
    
    <!-- Edit Company Modal -->
    <div id="edit_form" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Company</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="/residencyUpdate" method="post" id="editForm">
                            @csrf
                        <div class="form-group">
                            <label>Company Name <span class="text-danger">*</span></label>
                            <input class="form-control" value="" id="residency_name" name="residency_name" type="text">
                            <input class="form-control" value="" id="residency_id" name="residency_id" type="hidden">
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Company Modal -->
    
    <!-- Delete Company Modal -->
    <div class="modal custom-modal fade" id="delete_form" role="dialog">
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
                            <form action="/residencyDelete" method="post">
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
                        url: "{{ route('residency') }}",
                    },

                    columns: [
                        {
                            "render": function() {
                                return i++;
                            }
                        },
                        {
                            data: 'residency',
                            name: 'residency',

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
    // $('.editButton').click(function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            $('#residency_'+key).val(value);
        });
    })
</script>


<script>
    $(document).on('click','.deleteButton',function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#residency_delete_'+key).val(value);
        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addForm").validate({
            rules: {
                residency: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "{{ route('isCompanyExists') }}",
                        data :{
                            "_token": "{{ csrf_token() }}",
                        }
                    }
                }
            },
            messages: {
                residency: {
                    required : 'Company name is required',
                    remote: 'Company already exists',
                }
            },
       });
       
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        
        $("#editForm").validate({
            rules: {
                residency_name: {
                    required : true,
                    remote: { 
                        type: 'post',
                        url: "{{ route('isCompanyExists') }}",
                        data: {
                            'id': function () {
                                return $('#residency_id').val();
                            },
                            "_token": "{{ csrf_token() }}",
                        },
                    }
                }
            },
            messages: {
                residency_name: {
                    required : 'Company name is required',
                    remote: 'Company already exists',
                }
            },
       });
       
    });
</script>