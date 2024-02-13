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
                    <h3 class="page-title">Civil Reports</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Civil Reports</li>
                    </ul>
                </div>
                
            </div>
        </div>           
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="{{route('civil_report')}}">
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
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search company_drp" name="company">
                            <option value="">Select Company</option>
                            <?php foreach ($company as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['company']) && $search['company']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Company</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search subcompany_drp" name="subcompany">
                            <option value="">Select SubCompany</option>
                            <?php foreach ($subcompany as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['subcompany']) && $search['subcompany']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">SubCompany</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select id="multiple-checkboxes" name="user_ids[]" multiple="multiple"> 
                            <option value="">Select User</option> 
                            @if(isset($user_list) && count($user_list) > 0)
                                @foreach($user_list as $key => $val)
                                @if(isset($search['user_ids']) && !empty($search['user_ids']))
                                    @php
                                    if (in_array($val->id, $search['user_ids'])) { 
                                        $selected = 'selected';
                                    } else { 
                                        $selected = '';
                                    } 
                                    @endphp
                                @endif
                                    <option value="{{$val->id}}" {{$selected ?? ''}}>{{$val->first_name}} {{$val->last_name}}</option>
                                @endforeach
                            @endif 
                        </select>  
                    </div> 
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="branch">
                            <option value="">Select Branch</option>
                            <?php foreach ($branch as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['branch']) && $search['branch']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Branch</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="department">
                            <option value="">Select Department</option>
                            <?php foreach ($department as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['department']) && $search['department']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Department</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="designation">
                            <option value="">Select Job Title</option>
                            <?php foreach ($designation as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['designation']) && $search['designation']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Job Title</label>
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
                                    <th>Company</th>
                                    <th>SubCompany</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php $i = 1; $civil_cost = 0;@endphp
                            	@if(isset($data_list))
                            	    @foreach($data_list as $data)
                                        @if(isset($data->employee_details) && !empty($data->employee_details))
                                            @php $civil_cost =  $data->employee_details->civil_cost ?? 0;
                                                $expi_c_id =  $data->employee_details->expi_c_id ?? '';
                                                if(!empty($expi_c_id)):
                                                    $exp_str = strtotime($expi_c_id);
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
                                    <td>{{(isset($data->employee_details) && !empty($data->employee_details->expi_c_id)) ? date('d, M Y', strtotime($data->employee_details->expi_c_id)) : ''}}</td>
                                    <td>{{(isset($data->employee_residency) && !empty($data->employee_residency)) ? $data->employee_residency->name : ''}}</td>
                                    <td>{{(isset($data->employee_subcompany) && !empty($data->employee_subcompany)) ? $data->employee_subcompany->name : ''}}</td>
                                    <td>KWD {{number_format($civil_cost,2) ?? 0}}</td>
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
            paging: true,
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
