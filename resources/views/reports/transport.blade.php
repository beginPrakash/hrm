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
        <form method="post" action="{{route('transport_report')}}">
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
                            <input class="form-control floating" type="text" name="car_name" id="car_name" value="<?php echo (isset($search['car_name']) && !empty($search['car_name'])) ? $search['car_name'] : ''; ?>">
                        <label class="focus-label">Car Name</label>
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
                        <select class="selectwith_search company_drp" name="subcompany">
                            <option value="">Select SubCompany</option>
                            <?php foreach ($company as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['subcompany']) && $search['subcompany']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">SubCompany</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus focused">
                            <input class="form-control floating" type="text" name="doc_name" id="doc_name" value="<?php echo (isset($search['doc_name']) && !empty($search['doc_name'])) ? $search['doc_name'] : ''; ?>">
                        <label class="focus-label">Document Name</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search" name="reg_type">
                            <option value="">Select Registraion Type</option>
                            <?php foreach ($reg_type as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['reg_type']) && $search['reg_type']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Registraion Type</label>
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
                                    <th>Car Name</th>
                                    <th>License Number</th>
                                    <th>Registration Type</th>
                                    <th>Document Name</th>
                                    <th>Document Number</th>
                                    <th>Company</th>
                                    <th>Sub Company</th>
                                    <th>Cost Renewal</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php $i = 1; $trans_cost = 0;@endphp
                            	@if(isset($data_list))
                            	    @foreach($data_list as $data)
                                        @php $trans_cost =  $data->cost ?? 0;
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
                                    <td>{{(isset($data->trans_detail) && !empty($data->trans_detail)) ? $data->trans_detail->car_name : ''}}</td>
                                    <td>{{(isset($data->trans_detail) && !empty($data->trans_detail)) ? $data->trans_detail->license_no : ''}}</td>
                                    <td>{{(isset($data->regis_type) && !empty($data->regis_type)) ? $data->regis_type->name : ''}}</td>
                                    <td>{{$data->doc_name ?? ''}}</td>
                                    <td>{{$data->doc_number ?? ''}}</td>
                                    <td>{{(isset($data->trans_detail->com_detail) && !empty($data->trans_detail->com_detail)) ? $data->trans_detail->com_detail->name : ''}}</td>
                                    <td>{{(isset($data->trans_detail->subcom_detail) && !empty($data->trans_detail->subcom_detail)) ? $data->trans_detail->subcom_detail->name : ''}}</td>
                                    <td>KWD {{number_format($trans_cost,2) ?? 0}}</td>
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
            dom: 'Bfrtip',
            buttons: [
                //'pdfHtml5'
            ]
        } );
    } );


    $(document).on('click','.download_btn',function(){
        $('.type_val').val('pdf');
    });

    $(document).on('click','.search_btn',function(){
        $('.type_val').val('');
    });

                                            
</script>
