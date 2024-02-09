@include('includes/header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
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
                    <h3 class="page-title">Reports</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ul>
                </div>
                
            </div>
        </div>           
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="{{route('passport_report')}}">
                    @csrf
            
            <input type="hidden" name="type" class="type_val">
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker expiry_date" type="text" name="expiry_date" id="expiry_date" value="<?php echo (isset($search['expiry_date']) && !empty($search['expiry_date'])) ? $search['expiry_date'] : ''; ?>">
                        </div>
                        <label class="focus-label">Expiry From Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker to_date" type="text" name="to_date" id="to_date" value="<?php echo (isset($search['to_date']) && !empty($search['to_date'])) ? $search['to_date'] : ''; ?>">
                        </div>
                        <label class="focus-label">Expiry To Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus focused">
                            <input class="form-control floating" type="text" name="emp_name" id="emp_name" value="<?php echo (isset($search['emp_name']) && !empty($search['emp_name'])) ? $search['emp_name'] : ''; ?>">
                        <label class="focus-label">Name</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="designation">
                            <option value="">Select Designation</option>
                            <?php foreach ($designation as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['designation']) && $search['designation']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Designation</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="is_passport">
                            <option value="">Is Passport</option>
                            <option value="1" {{(isset($search['is_passport']) && $search['is_passport'] == '1') ? 'selected' : ''}}>Yes</option>
                            <option value="0" {{(isset($search['is_passport']) && $search['is_passport'] == '0') ? 'selected' : ''}}>No</option>
                        </select>
                        <label class="focus-label">Is Passport</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="hiring_type">
                            <option value="">Select Hiring Type</option>
                            <option value="local" {{(isset($search['hiring_type']) && $search['hiring_type'] == 'local') ? 'selected' : ''}}>Local</option>
                            <option value="oversease" {{(isset($search['hiring_type']) && $search['hiring_type'] == 'oversease') ? 'selected' : ''}}>Oversease</option>
                        </select>
                        <label class="focus-label">Hiring Type</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="status">
                            <option value="">Select Status</option>
                            <option value="active" {{(isset($search['status']) && $search['status'] == 'active') ? 'selected' : ''}}>Active</option>
                            <option value="expired" {{(isset($search['status']) && $search['status'] == 'expired') ? 'selected' : ''}}>Expired</option>
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-2">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success w-100 search_btn"> Search </button> 
                    </div>  
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="d-grid"> 
                        <button type="submit" class="btn add-btn download_btn"><i class="fa fa-download"></i>Download</button> 
                    </div>  
                </div>
            </div>
        </form>

        <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatablex" id="datatable">
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Civil Id</th>
                                    <th>Designation</th>
                                    <th>Date Of Joining</th>
                                    <th>Expired</th>
                                    <th>Is Passport</th>
                                    <th>Hiring Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php $i = 1; $civil_cost = 0;@endphp
                            	@if(isset($data_list))
                            	    @foreach($data_list as $data)
                                        @if(isset($data->employee_details) && !empty($data->employee_details))
                                            @php $civil_cost =  $data->employee_details->civil_cost ?? 0;
                                                $passport_expiry =  $data->passport_expiry ?? '';
                                                if(!empty($passport_expiry)):
                                                    $exp_str = strtotime($passport_expiry);
                                                    $cur_str = strtotime(date('Y-m-d'));
                                                    if($exp_str < $cur_str):
                                                        $status = 'Expired';
                                                    else:
                                                        $status = 'Active';
                                                    endif;
                                                else:
                                                    $status = '';
                                                endif;
                                            @endphp
                                        @endif
                                <tr>
                                    <td>
                                        {{$i}}
                                    </td>
                                    <td>{{$data->emp_generated_id}}</td>
                                    <td>{{$data->first_name}} {{$data->last_name}}</td>
                                    <td>{{(isset($data->employee_details) && !empty($data->employee_details)) ? $data->employee_details->c_id : ''}}</td>
                                    <td>{{(isset($data->employee_designation) && !empty($data->employee_designation)) ? $data->employee_designation->name : ''}}</td>
                                    <td>{{date('d, M Y', strtotime($data->joining_date))}}</td>
                                    <td>{{(isset($data) && !empty($data->passport_expiry)) ? date('d, M Y', strtotime($data->passport_expiry)) : ''}}</td>
                                    <td>{{($data->is_passport==1) ? 'Yes' : 'No'}}</td>
                                    <td>{{ucfirst($data->hiring_type) ?? ''}}</td>
                                    <td>{{$status}}</td>
                                </tr>
                                @php $i++; @endphp
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

</div>
<!-- /Page Wrapper -->


</div>


</body>


</html>

@include('includes/footer')   
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script> 
<script>
    $(document).ready(function() {
        // $('#multiple-checkboxes').multiselect();
        $('.selectwith_search').select2({
            minimumResultsForSearch: 1,
            width: '100%'
        });

        $('.type_val').val('');
        $('#datatable').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                //'pdfHtml5'
            ]
        } );
    } );

    $("#multiple-checkboxes").select2({
			closeOnSelect : false,
			placeholder : "Select User",
			allowHtml: true,
			allowClear: true,
			tags: true ,
            width: '100%'
		});

    $(document).on('click','.download_btn',function(){
        $('.type_val').val('pdf');
    });

    $(document).on('click','.search_btn',function(){
        $('.type_val').val('');
    });

    $(document).on('change','.company_drp',function(){
        var id= $(this).val();
        var sid= $('.subcompany_drp').val();
        $.ajax({
            url: "{{route('blistuserbycompany')}}",
            type: "POST",
            dataType: "json",
            data: {"_token": "{{ csrf_token() }}", id:id,sid:sid},
            success:function(response)
                {
                    console.log(response);
                    $('#multiple-checkboxes').html(response.res).fadeIn();
                }
        });
    });

    $(document).on('change','.subcompany_drp',function(){
        var sid= $(this).val();
        var id= $('.company_drp').val();
        $.ajax({
            url: "{{route('blistuserbycompany')}}",
            type: "POST",
            dataType: "json",
            data: {"_token": "{{ csrf_token() }}", id:id,sid:sid},
            success:function(response)
                {
                    $('#multiple-checkboxes').html(response.res).fadeIn();
                }
        });
    });
                                            
</script>
