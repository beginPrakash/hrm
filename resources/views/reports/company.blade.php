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
        <form method="post" action="{{route('company_report')}}">
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
                            <input class="form-control floating reg_name" type="text" name="reg_name" id="reg_name" value="<?php echo (isset($search['reg_name']) && !empty($search['reg_name'])) ? $search['reg_name'] : ''; ?>">
                        <label class="focus-label">Registration Name</label>
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
                        <select class="selectwith_search" name="status">
                            <option value="">Select Status</option>
                            <option value="active" {{(isset($search['status']) && $search['status'] == 'active') ? 'selected' : ''}}>Active</option>
                            <option value="expired" {{(isset($search['status']) && $search['status'] == 'expired') ? 'selected' : ''}}>Expired</option>
                        </select>
                        <label class="focus-label">status</label>
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
                                    <th>Registration Name</th>
                                    <th>Registration No.</th>
                                    <th>Branch</th>
                                    <th>Registration Type</th>
                                    <th>Company</th>
                                    <th>Sub Company</th>
                                    <th>Cost Renewal</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php $i = 1; $company_cost = 0;@endphp
                            	@if(isset($data_list))
                            	    @foreach($data_list as $data)
                                        @php $company_cost =  $data->cost ?? 0;
                                            $expiry_date =  $data->expiry_date ?? '';
                                            if(!empty($expiry_date)):
                                                $exp_str = strtotime($expiry_date);
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
                                <tr>
                                    <td>
                                        {{$i}}
                                    </td>
                                    <td>{{$data->reg_name}}</td>
                                    <td>{{$data->reg_no}}</td>
                                    <td>{{(isset($data->branch_details) && !empty($data->branch_details)) ? $data->branch_details->name : ''}}</td>
                                    <td>{{(isset($data->regis_type) && !empty($data->regis_type)) ? $data->regis_type->name : ''}}</td>
                                    <td>{{(isset($data->company_details) && !empty($data->company_details)) ? $data->company_details->name : ''}}</td>
                                    <td></td>
                                    <td>KWD {{number_format($company_cost,2) ?? 0}}</td>
                                    <td>{{date('d, M Y', strtotime($data->expiry_date))}}</td>
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


    $(document).on('click','.download_btn',function(){
        $('.type_val').val('pdf');
    });

    $(document).on('click','.search_btn',function(){
        $('.type_val').val('');
    });

                                            
</script>
