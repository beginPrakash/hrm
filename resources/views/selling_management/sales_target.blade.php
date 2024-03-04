@include('includes/header')
<link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
<link href="{{ asset('assets/css/bootstrap-new.css') }}" rel="stylesheet"/>
@include('includes/sidebar')
   <!-- Page Wrapper -->
<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
        <div class="alert alert-success alert-block sale_suc_msg" style="display:none">
            <button type="button" class="close" data-bs-dismiss="alert">×</button>    
            <strong class="succ_msg"></strong>
        </div>   
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Sales Target</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sales Target</li>
                    </ul>
                </div>
                
            </div>
        </div>    
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="{{route('sales_target.list')}}" id="search_form">
            @csrf
            <div class="row">
                <div class="col-sm-6 col-md-2"> 
                    <div class="form-group form-focus select-focus">
                    <?php
                        $monthArray = array(
                            '01'    =>  'Jan', '02' => 'Feb', '03' => 'Mar',
                            '04'    =>  'Apr', '05' => 'May', '06' => 'Jun',
                            '07'    =>  'Jul', '08' => 'Aug', '09' => 'Sep',
                            '10'    =>  'Oct', '11' => 'Nov', '12' => 'Dec',
                        );
                        ?>
                        <select class="select floating" name="month" id="report_month"> 
                            <option value="">-</option>
                            <?php foreach($monthArray as $makey => $ma) { ?>
                                <option value="<?php echo $makey; ?>" {{(isset($search['month']) && $search['month'] == $makey) ? 'selected' : ''}}><?php echo $ma; ?></option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Select Month</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" 
                                id="dropdownMenu1" data-toggle="dropdown" 
                                aria-haspopup="true" aria-expanded="true">
                            Select Company
                        
                        </button>
                        <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
                            @php $selected = ''; @endphp
                            @if(isset($company_list) && count($company_list) > 0)  
                                @foreach($company_list  as $key => $val)
                                    @if(isset($search['company']) && !empty($search['company']))
                                        @php
                                        if (in_array($key, $search['company'])) { 
                                            $selected = 'checked';
                                        } else { 
                                            $selected = '';
                                        } 
                                        @endphp
                                    @endif
                                    <li>
                                    <label>
                                        <input type="checkbox" class="company_check" name="company[]" value="{{$key}}" {{$selected}}> {{$val}}
                                    </label>
                                    </li>
                                @endforeach
                            @endif
                            
                        </ul>
                        </div>
                    </div>
                </div>
               
                <div class="col-sm-6 col-md-2"> 
                    <div class="form-group form-focus select-focus">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle branch_checklist" type="button" 
                                    id="dropdownMenu1" data-toggle="dropdown" 
                                    aria-haspopup="true" aria-expanded="true">
                                Select Branch
                            
                            </button>
                            <ul class="dropdown-menu checkbox-menu allow-focus branch_menu" aria-labelledby="dropdownMenu1">
                                
                               
                                @if(isset($b_list) && count($b_list) > 0)  
                                    @php $res = ''; @endphp
                                    @foreach($b_list  as $key => $val)
                                    @php $selected = ''; @endphp
                                        @if(isset($search['brnach_list']) && !empty($search['brnach_list']))
                                            @php
                                            if (in_array($val->id, $search['brnach_list'])) { 
                                                $selected = 'checked';
                                            } else { 
                                                $selected = '';
                                            } 
                                            @endphp
                                        @endif
                                        @if($res != $val->residency)
                                            @php $res = $val->residency; @endphp
                                            @if($key != 0)
                                            <hr class="hr_line">
                                            @endif
                                        
                                        @endif
                                        <li>
                                        <label>
                                            <input type="checkbox" class="branch_check" name="brnach_list[]" value="{{$val->id}}" {{$selected}}>{{$val->name}}
                                        </label>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>  
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle sells_checklist" type="button" 
                                id="dropdownMenu3" data-toggle="dropdown" 
                                aria-haspopup="true" aria-expanded="true">
                            Select Selling Period
                        
                        </button>
                        <ul class="dropdown-menu checkbox-menu allow-focus sells_menu" aria-labelledby="dropdownMenu1">
                                @php $selected = ''; @endphp
                                @if(isset($s_list) && count($s_list) > 0)  
                                    @foreach($s_list  as $key => $val)
                                        @if(isset($search['sells_list']) && !empty($search['sells_list']))
                                            @php
                                            if (in_array($val->id, $search['sells_list'])) { 
                                                $selected = 'checked';
                                            } else { 
                                                $selected = '';
                                            } 
                                            @endphp
                                        @endif
                                        <li>
                                        <label>
                                            <input type="checkbox" name="sells_list[]" value="{{$val->id}}" {{$selected}}> {{$val->item_name}}
                                        </label>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-2">
                    <div class="d-grid"> 
                        <button type="submit" id="fwb" class="btn add-btn">Next    <i class="fa fa-arrow-right"></i></button> 
                    </div>  
                </div>
            </div>
        </form>
       
        <div class="page-header sales_target">
            <div class="row align-items-center">
                <div class="col">    
                    @if(isset($search['company']) && !empty($search['company']))
                        @foreach($search['company'] as $key => $val)
                            <h4>{{_get_company_name($val)}}</h4>
                             <!-- /Page Header -->
        
                                @if(isset($search['brnach_list']) && !empty($search['brnach_list']))
                                    @foreach($search['brnach_list'] as $bkey => $bval)
                                        @php $branch_name = _get_branch_name_by_comapny($bval,$val); 
                                        $scou = 0;
                                        @endphp
                                        @if(!empty($branch_name))
                                        <div class="card target_sectiondiv{{$bkey}}">
                                            <div class="card-body">
                                                <form action="#" class="store_form_{{$bval}}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="profile-view">
                                                                <div class="">
                                                                    <div class="">
                                                                        <h3>{{_get_branch_name_by_comapny($bval,$val)}}</h3>
                                                                        @if(isset($search['sells_list']) && !empty($search['sells_list']))
                                                                            @foreach($search['sells_list'] as $skey => $sval)
                                                                                @php $s_period_name = _get_sellingperiod_by_comapny($sval,$val,$bval); @endphp
                                                                                @if(!empty($s_period_name))
                                                                                @php $scou++; 
                                                                                    $sales_detail = _get_sales_master_data_by_id($val,$bval,$sval,$search['month']);
                                                                                    $sales_detail_sum = _get_sales_master_sum_by_id($val,$bval,$sval,$search['month']);
                                                                                @endphp
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label>{{$s_period_name}}</label>
                                                                                            <input type="text" name="target_price[]" class="form-control allowfloatnumber period_cost"  data-id="{{$bkey}}" placeholder="Enter value" value="{{$sales_detail->target_price ?? ''}}">
                                                                                            <input type="hidden" name="sales_tar_id[]" class="sales_tar_id_{{$bval}}" value="{{$sales_detail->id ?? ''}}">
                                                                                            <input type="hidden" name="branch_id" value="{{$bval}}">
                                                                                            <input type="hidden" name="company_id" value="{{$val}}">
                                                                                            <input type="hidden" name="sell_id[]" value="{{$sval}}">
                                                                                            <input type="hidden" name="per_day_price[]" class="sell_p_perday_{{$bkey}}" value="{{$sales_detail->per_day_price ?? ''}}">
                                                                                            <input type="hidden" name="month" value="{{$search['month'] ?? ''}}">
                                                                                            @if(!empty($sales_detail->per_day_price))
                                                                                                <span class="period_span">{{$sales_detail->per_day_price ?? ''}} Per Day</span>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                            @if($scou > 0)
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label>Total</label>
                                                                                        </div>
                                                                                        <span class="total_period_{{$bkey}}"> {{(!empty($sales_detail_sum)) ? number_format($sales_detail_sum,2) : ''}} {{(!empty($sales_detail_sum)) ? 'Per Day' : ''}}</span>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <button type="button" name="update" id="white-button" class="btn btn-primary submit-btn save_store_btn" data-id="{{$bval}}">Save</button>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="pro-edit"><a href="javascript:void(0);" class="delete_target_sec" data-id="{{$bkey}}" data-sale_id="{{serialize($search['sells_list']) ?? ''}}" data-month="{{$search['month'] ?? ''}}"><i class="fa fa-close"></i></a></div>
                                                            </div>
                                                        </div>
                                                    </div>                                                 
                                                </form>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif
                                
                        @endforeach
                    @endif
                </div>
                
            </div>
        </div> 
    </div>
    <!-- /Page Content -->
    

</div>
<!-- /Page Wrapper -->


</div>


</body>


</html>

@include('includes/footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script type="text/javascript">
    $(document).on('click','.company_check',function(){
        $('.branch_menu').remove();
        var sel_val = $('.company_check:checked').map(function() {
            return this.value;
        }).get().join(',');

        $.ajax({
            url: "{{route('sales_target.branchlistbycompany')}}",
            type: "POST",
            dataType: "json",
            data: {"_token": "{{ csrf_token() }}", sel_val:sel_val},
            success:function(response)
                {
                    $('.branch_checklist').after(response.html).fadeIn();
                }
        });
    });

    $(document).on('click','.branch_check',function(){
        $('.sells_menu').remove();
        var sel_val = $('.branch_check:checked').map(function() {
            return this.value;
        }).get().join(',');
        var company_id = $('.company_check:checked').map(function() {
            return this.value;
        }).get().join(',');

        $.ajax({
            url: "{{route('sales_target.sellplistbycompany')}}",
            type: "POST",
            dataType: "json",
            data: {"_token": "{{ csrf_token() }}", sel_val:sel_val,company_id:company_id},
            success:function(response)
                {
                    $('.sells_checklist').after(response.html).fadeIn();
                }
        });
    });

    $(document).on('click','.edit_branch',function(){
        $('#add_Form').html('');
        var id= $(this).attr('data-id');
        $.ajax({
           url: "{{route('getsellingdetaiById')}}",
           type: "POST",
           dataType: "json",
           data: {"_token": "{{ csrf_token() }}", id:id},
           success:function(response)
            {
                $('#add_Form').html(response.html).fadeIn();
            }
        });
    });

    $(document).on('click','.delete_branch',function(){
        var id= $(this).attr('data-id');
        $('#selling_delete_id').val(id);
    });

    $('.stable').on('click','#flexSwitchCheckChecked', function (e) {
		var url = $(this).attr("data-url");
		 location.href=url;
	});

    $(document).on('click','.save_store_btn',function(){
        var url ="{{route('sales_target.store')}}";
        var id= $(this).attr('data-id');
        $.ajax({
           url: "{{route('sales_target.store')}}",
           type: "POST",
           dataType: "json",
           data: $('.store_form_'+id).serialize(),
           success:function(response)
            {
                if(response.sal_id != ''){
                    $('.sales_tar_id_'+id).val(response.sal_id);
                    $('.sale_suc_msg').show();
                    $('.succ_msg').text(response.msg);
                }
            }
        });
    });

    $("#add_Form").on("hidden.bs.modal", function(){
        
        $("#is_bill_count").prop('checked',false);
        $('#item_name').val('');
        $('.leave_m_title').text('Create Selling Period');
    });

    $(".allowfloatnumber").keypress(function (eve) {
        if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
            eve.preventDefault();
        }

        // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
        $('.allowfloatnumber').keyup(function(eve) {
                if ($(this).val().indexOf('.') == 0) {
                $(this).val($(this).val().substring(1));
                }
            });
    });

    var days = 0;
    const getDays = (year, month) => new Date(year, month, 0).getDate()
    var sel_mon = $('#report_month').val();
    if(sel_mon != ''){
        days = getDays(new Date().getFullYear(), sel_mon)
    }

    
    $('.period_cost').keyup(function(eve){
        var total = 0;
        $(this).parent().find('.period_span').remove();
        var target_val = $(this).val();
        var id = $(this).attr('data-id');
        var cal_val = target_val / days;
        $(this).after('<span class="period_span">'+cal_val.toFixed(2)+' Per Day</span>');
        $(this).parent().find('.sell_p_perday_'+id).val(cal_val);
        
    });

    $(document).on('click','.delete_target_sec',function(){
        var id = $(this).attr('data-id');
        var sale_id = $(this).attr('data-sale_id');
        var month = $(this).attr('data-month');
        $.ajax({
           url: "{{route('sales_target.delete')}}",
           type: "POST",
           dataType: "json",
           data: {"_token": "{{ csrf_token() }}", sale_id:sale_id,month:month},
           success:function(response)
            {
                //if(sale_id != ''){
                    $('.sale_suc_msg').show();
                    $('.succ_msg').text(response.msg);
                //}
            }
        });
        $('.target_sectiondiv'+id).remove();
    });

    var totalPrice = 0;
    
    $('.period_cost').keydown(function(eve){
        var id = $(this).attr('data-id');
        var queryArr = [];
        $('.sell_p_perday_'+id).each(function(){
            
            if (eve.which == 9) {
                   var sum =  parseFloat(this.value);
                   totalPrice += Number(sum); 
                   queryArr.push(this.value);   
            }
        });
        queryArr = queryArr.map(Number);

        var total = queryArr.reduce(function(a,b){  return a+b },0)
        if(parseInt(total) > 0){
            $(this).parents().find('.total_period_'+id).html(total.toFixed(2)+' Per Day');
        }else{
            $(this).parents().find('.total_period_'+id).html('');
        }
    });
   
</script>
