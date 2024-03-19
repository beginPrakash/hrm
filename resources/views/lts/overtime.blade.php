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
                                <h3 class="page-title">Overtime</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Overtime</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                
                                
                                <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_leave"><i class="fa fa-plus"></i> Add Overtime</a>
                                <a href="#" class="btn add-btn m-r-5" data-bs-toggle="modal" data-bs-target="#import_overtime"> Import Overtime</a>
                                <a href="{{route('master_ot_export')}}" class="btn add-btn m-r-5"><i class="fa fa-download"></i>Sample CSV</a>
                                
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <!-- Search Filter -->
                    <form action="{{route('master_ot')}}" method="post">
                        @csrf
                        <div class="row filter-row">
                            <div class="col-sm-6 col-md-3">  
                                <div class="form-group form-focus focused">
                                    <div class="cal-icon">
                                        <input class="form-control floating datetimepicker" type="text" name="from_date" id="from_date" value="<?php echo (isset($search['from_date']) && !empty($search['from_date'])) ? $search['from_date'] : ''; ?>">
                                    </div>
                                    <label class="focus-label">From Date</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">  
                                <div class="form-group form-focus focused">
                                    <div class="cal-icon">
                                        <input class="form-control floating datetimepicker to_date" type="text" name="to_date" id="to_date" value="<?php echo (isset($search['to_date']) && !empty($search['to_date'])) ? $search['to_date'] : ''; ?>">
                                    </div>
                                    <label class="focus-label">To Date</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">  
                                <div class="form-group form-focus focused">
                                    <input class="form-control" type="text" name="search_text" id="search_text" placeholder="Search by userId and name" value="<?php echo (isset($search['search_text']) && !empty($search['search_text'])) ? $search['search_text'] : ''; ?>">
                                    <label class="focus-label">Employee Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2">  
                                <input type="submit" class="btn btn-success w-100" name="search" value="search"> 
                            </div>     
                        </div>
                    </form>
                    <!-- Search Filter -->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class=" table table-bordered table-striped table-hover datatable datatable-LoanApplication">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;">User Id</th>
                                            <th>Employee Name</th>
                                            <th>OT Date</th>
                                            <th>OT Hours</th>
                                            <th>Description</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($overtime_data))
                                        {
                                            $i = 0;
                                            foreach($overtime_data as $data)
                                            {
                                                $i++;

                                                if(isset($data->employee) && $data->employee->profile != Null)
                                                {
                                                    $image = 'uploads/profile/'.$data->employee->profile;
                                                }else{
                                                    $image = 'assets/img/profiles/avatar.png';
                                                }
                                                
                                            ?>
                                                <tr>
                                                    <td>{{(isset($data->employee) && !empty($data->employee)) ? $data->employee->emp_generated_id : ''}}</td>
                                                    <td>
                                                    <h2 class="table-avatar">
                                                        <a class="avatar avatar-xs" href="{{'/employeeProfileUpdate?id='.$data->employee_id ?? ''}}"><img alt="" src="<?php echo $image; ?>"></a>
                                                        <a href="{{'/employeeProfileUpdate?id='.$data->employee_id ?? ''}}">{{(isset($data->employee) && !empty($data->employee)) ? $data->employee->first_name.' '.$data->employee->last_name : ''}}</a>
                                                    </h2>
                                                    </td>
                                                    <td>{{date('d-m-Y', strtotime($data->ot_date))}}</td>
                                                    <td>{{$data->ot_hours}}</td>
                                                    <td>{{$data->description}}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_leave" data-id="{{$data->id}}" class="action-icon edit_hierarchy"><i class="fa fa-pencil"></i></a>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#delete_overtime" data-id="{{$data->id}}" class="delete_overtime"><i class="fa fa-trash-o m-r-5" ></i></a>
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
                <div id="add_leave" class="modal custom-modal fade modal_div" role="dialog">
                    @include('lts/overtime_modal')
                </div>
                <!-- /Add Leave Modal -->

                <!-- Delete Bonus Modal -->
                <div class="modal custom-modal fade" id="delete_overtime" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Delete Overtime</h3>
                                    <p>Are you sure want to delete?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <div class="row">
                                        <div class="col-6">
                                            <form action="{{route('master_ot.delete')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="ot_id" id="ot_id" value="">
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

                <!-- Import Bonus Modal -->
                <div class="modal custom-modal fade" id="import_overtime" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Import Overtime</h3>
                                </div>
                                <div class="modal-btn import-action">
                                    <div class="row">
                                        <div class="col-12">
                                            <form action="{{route('master_ot_import')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Import File <span class="text-danger">*</span></label>
                                                    <input class="form-control" value="" readonly type="file" name="ot_file">
                                                </div>
                                                <div class="submit-section">
                                                    <button class="btn btn-primary submit-btn">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Import Bonus Modal -->
   
            </div>
            <!-- /Page Wrapper -->


@include('includes/footer')

<script type="text/javascript">
    $('.user_select').select2({
        minimumResultsForSearch: 4,
        width: '100%',
        //allowClear: true,
        dropdownParent: $(".modal_div"),
    });

    $(document).ready(function() {
        $("#addEditForm").validate({
            rules: {
                employee_id: {
                    required : true},
                ot_date:  {
                    required : true},
                ot_hours:  {
                    required : true},
                description:  {
                    required : true
                },
            },    
            messages: {
                employee_id: {
                    required : 'Employee is required',
                },
                ot_date: {
                    required : 'OverTime Date is required',
                },
                ot_hours: {
                    required : 'OverTime hours is required',
                },
                description: {
                    required : 'Description is required',
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
            url: "{{route('master_ot.details')}}",
            type: "POST",
            dataType: "json",
            data: {"_token": "{{ csrf_token() }}", id:id},
            success:function(response)
                {
                    $('#add_leave').html(response.html).fadeIn();
                }
            });
        });

        $(document).on('click','.delete_overtime',function(){
            var id= $(this).attr('data-id');
            $('#ot_id').val(id);
        });

       $('#add_leave').on('hidden.bs.modal', function () {
            $('#employee_id').val('').trigger('change');
            $('#ot_date').val('');
            $('#ot_hours').val('');
            $('#description').val('');
            $('.leave_m_title').text('Add OverTime');
        });
       
    });

    
</script>
