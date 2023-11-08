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
                                <h3 class="page-title">Bonus</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Bonus</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_leave"><i class="fa fa-plus"></i> Add Bonus</a>
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
                                            <th style="width: 30px;">#</th>
                                            <th>Employee Name</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($bonus_data))
                                        {
                                            $i = 0;
                                            foreach($bonus_data as $data)
                                            {
                                                $i++;
                                                
                                            ?>
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{(isset($data->employee) && !empty($data->employee)) ? $data->employee->first_name : ''}}</td>
                                                    <td>{{date('d-m-Y', strtotime($data->bonus_date))}}</td>
                                                    <td>{{$data->bonus_amount}}</td>
                                                    
                                                    <td>
                                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_leave" data-id="{{$data->id}}" class="action-icon edit_hierarchy"><i class="fa fa-pencil"></i></a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#delete_bonus" data-id="{{$data->id}}" class="delete_bonus"><i class="fa fa-trash-o m-r-5" ></i></a>
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
                
                <!-- Add Leave Modal -->
                <div id="add_leave" class="modal custom-modal fade" role="dialog">
                    @include('lts/bonus_modal')
                </div>
                <!-- /Add Leave Modal -->

                <!-- Delete Bonus Modal -->
                <div class="modal custom-modal fade" id="delete_bonus" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Delete Bonus</h3>
                                    <p>Are you sure want to delete?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <div class="row">
                                        <div class="col-6">
                                            <form action="/delete_bonus" method="post">
                                                @csrf
                                                <input type="hidden" name="bonus_id" id="bonus_id" value="">
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
                <!-- /Delete Bonus Modal -->
   
            </div>
            <!-- /Page Wrapper -->


@include('includes/footer')

<script type="text/javascript">
    $(document).ready(function() {
          $("#addEditForm").validate({
        rules: {
            user_id: {
                required : true},
            bonus_date:  {
                required : true},
            bonus_amount:  {
                required : true},
            title:  {
                required : true
            },
        },    
        messages: {
            user_id: {
                required : 'User is required',
            },
            bonus_date: {
                required : 'Bonus Date is required',
            }
            ,
            bonus_amount: {
                required : 'Bonus Amount is required',
            },
            title: {
                required : 'Title is required',
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

       $(document).on('click','.edit_hierarchy',function(){
            $('#add_leave').html('');
            var id= $(this).attr('data-id');
            $.ajax({
            url: '/bonus_details/',
            type: "POST",
            dataType: "json",
            data: {"_token": "{{ csrf_token() }}", id:id},
            success:function(response)
                {
                    $('#add_leave').html(response.html).fadeIn();
                }
            });
        });

        $(document).on('click','.delete_bonus',function(){
            var id= $(this).attr('data-id');
            $('#bonus_id').val(id);
        });

       $('#add_leave').on('hidden.bs.modal', function () {
            $('#employee_id').val('').trigger('change');
            $('#bonus_date').val('');
            $('#bonus_amount').val(0);
            $('#title').val('');
            $('.leave_m_title').text('Add Bonus');
        });
       
    });

    
</script>
