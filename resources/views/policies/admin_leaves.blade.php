@include('includes/header')
@include('includes/sidebar')


 <!-- Page Wrapper -->
            <div class="page-wrapper">

                <!-- Page Content -->
                <div class="content container-fluid">
                    
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Leaves Hierarchy</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Leaves Hierarchy</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_leave"><i class="fa fa-plus"></i> Add Leave Hierarchy</a>
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
                                            <th>Leave Type</th>
                                            <th>Department</th>
                                            <th>Title</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($leaveApplications) && count($leaveApplications) > 0)
                                            @foreach($leaveApplications as $k => $v)
                                            <tr>
                                                <td>{{(isset($v->leaves_leavetype) && !empty($v->leaves_leavetype)) ? $v->leaves_leavetype->name : ''}}</td>
                                                <td>{{(isset($v->department_detail) && !empty($v->department_detail)) ? $v->department_detail->name : ''}}</td>
                                                <td>{{(isset($v->designation_detail) && !empty($v->designation_detail)) ? $v->designation_detail->name : ''}}</td>
                                                <td>
                                                    <div class="pull-right">
                                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_leave" class="action-icon edit_hierarchy" data-id="{{$v->id}}"><i class="fa fa-pencil"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Page Content -->
                
                <!-- Add Leave Modal -->
                <div id="add_leave" class="modal custom-modal fade" role="dialog">
                    @include('policies/admin_leave_modal')
                </div>
                <!-- /Add Leave Modal -->

                
            </div>
            <!-- /Page Wrapper -->


@include('includes/footer')

<script type="text/javascript">
    $(document).ready(function() {
        // Add More Dept
        $('.add_more_dept_btn').click(function() {
           
            var element = $('.add_dept_div:last').clone();
            var j = $('.add_dept_div').length;
            element.insertAfter($(this).parents().find('.add_dept_div:last'));
            
            if(j>=1){
                $('.dept_select:last').attr('id','dept_select'+j);
                $('#dept_select'+j).select2('destroy');
                $('#dept_select'+j).select2();
                $('.title_select:last').attr('id','title_select'+j);
                $('#title_select'+j).select2('destroy');
                $('#title_select'+j).select2();
            }
            if(j == 1){
                  $(".add_more_dept_btn:last").remove();
                  $('.add_btn_div:last').append('<button type="button" class="btn btn-primary plus-minus remove_dept_btn"><i class="fas fa-minus"></i></button>');
              }
            j++;
            if ($('.agenda_div').length > 1) {
                $('.agenda_div').find('.remove_agenda').show();
            }
        });

        //remove row when click remove button
        $(document).on('click','.remove_dept_btn',function(){
            $(this).closest('div').parent().remove();
        });

        $("#admin_leaves_form").validate({
            rules: {
                leave_type: {
                    required : true},
                main_department:  {
                    required : true},
                main_title:  {
                    required : true},
                // 'sub_department[]':  {
                //     required : true},
                // 'sub_title[]':  {
                //     required : true},
            },
            messages: {
                leave_type: {
                    required : 'Leave Type is required',
                },
                main_department: {
                    required : 'Please select department',
                }
                ,
                main_title: {
                    required : 'Please select title',
                },
                // 'sub_department[]': {
                //     required : 'Please select department',
                // },
                // 'sub_title[]': {
                //     required : 'Please select title',
                // }
            },
            errorPlacement: function (error, element) {
                if (element.prop("type") == "text") {
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
           url: '/getLeaveDetailsById/',
           type: "POST",
           dataType: "json",
           data: {"_token": "{{ csrf_token() }}", id:id},
           success:function(response)
            {
                $('#add_leave').html(response.html).fadeIn();
            }
        });
    });
    $("#add_leave").on("hidden.bs.modal", function(){
        
        $(".select").val(null).trigger("change");
        $('.leave_id').val('');
        $('.add_dept_div').slice(1).remove();
        $('.leave_m_title').text('Add Leave Hierarchy');
    });

  
</script>

<script>
    $(document).on('click','.approveButton',function(){
        var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#leave_id').val(lid);
        $('#approval_by').val(id);
    })
</script>

<script>
    $(document).on('click','.rejectButton',function(){
        var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#reject_leave_id').val(lid);
        $('#reject_by').val(id);
    })
</script>

<script>
    $(document).on('click','.cancelButton',function(){
        // var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#cancel_leave_id').val(lid);
        // $('#cancel_by').val(id);
    })
</script>

<script type="text/javascript">
    $(document).on('change','#leave_type, #leave_leave_type',function(){
        var leave_type = $(this).val();
        var max_leaves = $(this).find(':selected').data('id');
        $('#addLeaveBtn').attr('disabled', false);
        $('#rl_count_err').text('');
        $.ajax({
           url: '/getLeaveDetails/',
           type: "POST",
           dataType: "json",
           data: {"_token": "{{ csrf_token() }}", leave_type:leave_type},
           success:function(response)
            {
                //response = taken leaves
                var remaining_leave = max_leaves - response;
                $('#remaining_leaves').val(remaining_leave);
                if(remaining_leave == 0)
                {
                    $('#addLeaveBtn').attr('disabled', true);
                    $('#rl_count_err').text('Insufficient no of leaves');
                }
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('change','#from_date, #to_date',function(){
        var from_date = $('#from_date').val();
        $('#to_date').attr('min', from_date);
        var to_date = $('#to_date').val();

        var dt1 = new Date(from_date);
        var dt2 = new Date(to_date);
 
        var time_difference = dt2.getTime() - dt1.getTime();
        var result = time_difference / (1000 * 60 * 60 * 24);

        var no_days = (result >= 0 )?result+1:0;
        $('#no_of_days').val(no_days);
    });

    $(document).on('change','#leave_leave_from, #leave_leave_to',function(){
        var from_date = $('#leave_leave_from').val();
        $('#leave_leave_to').attr('min', from_date);
        var to_date = $('#leave_leave_to').val();

        var dt1 = new Date(from_date);
        var dt2 = new Date(to_date);
 
        var time_difference = dt2.getTime() - dt1.getTime();
        var result = time_difference / (1000 * 60 * 60 * 24);
  
        var no_days = (result >= 0 )?result+1:0;
        $('#leave_leave_days').val(no_days);
    });
</script>

<script>
    $(document).on('click','.editButton',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            // console.log(key);
            $('#leave_'+key).val(value);
            if(key == 'leave_type')
            {
                $("#leave_leave_type").val(value).change();
            }
        });
    })
</script>