@include('includes/header')
@include('includes/sidebar')
   <!-- Page Wrapper -->
<!-- Page Wrapper -->
<link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>

<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
        @include('flash-message')   
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Daily Sales</h3>
                </div>
            </div>
        </div>    
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="{{route('store_daily_sales.list')}}" id="search_form">
            @csrf
            <div class="row">
                <div class="col">
                    <h3 class="page-title">{{(isset($user->employee_branch) && !empty($user->employee_branch->name)) ? $user->employee_branch->name : ''}}</h3>    
                </div>
                
                <div class="col-sm-6 col-md-2">  
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text" name="search_date" id="search_date" value="<?php echo (isset($search['search_date']) && !empty($search['search_date']))? $search['search_date']: date('d-m-Y'); ?>">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" 
                                    id="dropdownMenu1" data-toggle="dropdown" 
                                    aria-haspopup="true" aria-expanded="true">
                                Select Selling Period
                            
                            </button>
                            <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
                                @if(isset($sells_p_data) && count($sells_p_data) > 0)  
                                    @foreach($sells_p_data  as $key => $val)
                                        @php $selected = ''; @endphp
                                        @if(isset($search['sells_id']) && !empty($search['sells_id']))
                                            @php
                                            if (in_array($key, $search['sells_id'])) { 
                                                $selected = 'checked';
                                            } else { 
                                                $selected = '';
                                            } 
                                            @endphp
                                        @endif
                                        <li>
                                        <label>
                                            <input type="checkbox" class="sells_check select_change" name="sells_id[]" value="{{$key}}" {{$selected ?? ''}}>{{$val}}
                                        </label>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div> 
                    </div>
                </div>
                <div class="col-sm-6 col-md-2 srch_btn">
                    <div class="d-grid"> 
                        <button type="submit" class="btn add-btn"><i class="fa fa-arrow"></i>Search</button> 
                    </div>  
                </div>
            </div>
        </form>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">Today Target</h2>
                                <span>{{number_format($today_target ?? 0,2)}} KWD</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">Today Sales</h2>
                                <span>{{number_format($today_sale ?? 0,2)}} KWD</span>
                                @if($today_target != $today_sale)
                                @php $calculate_per = _calculate_per($today_target,$today_sale); @endphp
                                {!!$calculate_per ?? '' !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">Today Variance</h2>
                                <span>{{number_format($today_vari ?? 0,2)}} KWD</span>
                                {!!$calculate_per ?? '' !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">Today Bill Avr</h2>
                                <span>{{number_format($today_bill_avg ?? 0,2)}} KWD</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">MTD Target</h2>
                                <span>{{number_format($mtd_target ?? 0,2)}} KWD</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">MTD Sale</h2>
                                <span>{{number_format($mtd_sale ?? 0,2)}} KWD</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">MTD Variance</h2>
                                <span>{{number_format($mtd_vari ?? 0,2)}} KWD</span>
                                @php $calculate_per = _calculate_per($mtd_target,$mtd_sale); @endphp
                                {!!$calculate_per!!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <div class="dash-widget-info text-center">
                                <h2 class="sales_title">MTD Bill Avr</h2>
                                <span>{{number_format($mtd_bill_avg ?? 0,2)}} KWD</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">  
                    @if(isset($search_sells_data) && count($search_sells_data) > 0)
                        @foreach($search_sells_data as $key => $val)
                        @php $target_price = _target_price_by_sell($val->company_id,$val->branch_id,$val->id,$search['search_date']); @endphp
                            @if(!empty($target_price))    
                                <div class="card target_sectiondiv">
                                    <div class="card-body">
                                    
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="profile-view">
                                                        <div class="profile-basic">
                                                            <div class="row">
                                                                <h3>{{$val->item_name}} Sale and tracking</h3>
                                                                <h4>Daily Sales</h4>
                                                                <form action="{{'store_daily_sales.save'}}" class="daily_sales_form_{{$val->id}}">
                                                                    @csrf
                                                                    @php $is_daily_sales_exists = _is_daily_sales_exists($val->company_id,$val->branch_id,$val->id,$search['search_date']); @endphp
                                                                    <input type="hidden" name="sells_p_id" value="{{$val->id}}">
                                                                    <input type="hidden" name="serch_date" value="{{$search['search_date'] ?? ''}}">
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Sales</label>
                                                                            <input type="text" name="achieve_target" class="form-control allowfloatnumber achieve_tar" data-id="{{$val->id}}" value="{{$is_daily_sales_exists->achieve_target ?? ''}}">
                                                                            <input type="hidden" class="achieve_target_{{$val->id}}" value="{{$is_daily_sales_exists->achieve_target ?? ''}}">
                                                                            <input type="hidden" name="target_price" value="{{$target_price}}">
                                                                            <span class="target_diff_pan">Target {{$target_price}} KWD</span>
                                                                            <input type="hidden" name="daily_sales_id" class="daily_sales_{{$val->id}}" value="{{$is_daily_sales_exists->id ?? ''}}">
                                                                        </div>
                                                                    </div>
                                                                    @if($val->is_bill_count == '1')
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Bill Count #C.A</label>
                                                                            <input type="text" name="bill_count" class="form-control allowfloatnumber bill_count_div" data-id="{{$val->id}}" value="{{$is_daily_sales_exists->bill_count ?? ''}}">
                                                                            <input type="hidden" name="bill_count_avg" class="bill_count_avg_div">
                                                                            <span class="bill_count_span">@if(isset($is_daily_sales_exists->avg_bill_count) && !empty($is_daily_sales_exists->avg_bill_count)) Avg Bill @endif {{$is_daily_sales_exists->avg_bill_count ?? ''}}</span>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </div>    
                                                                    <h4>Daily Tracking</h4>
                                                                    <div class="row">
                                                                    @php $heading_list = _tracking_heading_by_speriod($val->company_id,$val->branch_id,$val->id);@endphp
                                                                    @if(isset($heading_list) && count($heading_list) > 0)
                                                                        @foreach($heading_list as $hkey => $hval)
                                                                        @php $selected = ''; @endphp
                                                                        @if(isset($is_daily_sales_exists->headings) && !empty($is_daily_sales_exists->headings))
                                                                            @php
                                                                            $headings = json_decode($is_daily_sales_exists->headings);
                                                                            @endphp
                                                                            @if(isset($headings) && count($headings) > 0)
                                                                                @foreach($headings as $ehkey => $ehval)
                                                                                    @if($ehval->id == $hval->id)
                                                                                        <input type="hidden" name="tracking_id" value="{{$hval->id}}">
                                                                                        <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label>{{ucfirst($hval->title)}}</label>
                                                                                                <input type="hidden" name="heading_price[{{$hkey}}][id]" value="{{$hval->id}}">
                                                                                                <input type="text" name="heading_price[{{$hkey}}][price]" class="form-control allowfloatnumber" value="{{$ehval->price}}">
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        @else
                                                                        <input type="hidden" name="tracking_id" value="{{$hval->id}}">
                                                                                <div class="col-md-3">
                                                                                    <div class="form-group">
                                                                                        <label>{{ucfirst($hval->title)}}</label>
                                                                                        <input type="hidden" name="heading_price[{{$hkey}}][id]" value="{{$hval->id}}">
                                                                                        <input type="text" name="heading_price[{{$hkey}}][price]" class="form-control allowfloatnumber">
                                                                                    </div>
                                                                                </div>
                                                                        @endif
                                                                        
                                                                        @endforeach
                                                                        
                                                                    @endif
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                        <label></label>
                                                                            <button type="button" class="btn btn-primary submit-btn save_store_btn" data-id="{{$val->id}}">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    
                                    </div>
                                </div>
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
<link href="{{ asset('assets/css/bootstrap-new.css') }}" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script type="text/javascript">

    $('.bill_count_div').keyup(function(eve){
        var bill_val = $(this).val();
        var id = $(this).attr('data-id');
        var target_val = parseFloat($('.achieve_target_'+id).val());
        if(target_val != '' && bill_val != ''){
            var avg_val = target_val/bill_val;
            $(this).parent().find('.bill_count_avg_div').val(avg_val.toFixed(2));
            $(this).parent().find('.bill_count_span').text('Avg Bill '+avg_val.toFixed(2));
        }
    });


    $('.achieve_tar').keyup(function(eve){
        var id= $(this).attr('data-id');
         $('.achieve_target_'+id).val($(this).val());
    });

    $(document).on('click','.save_store_btn',function(){
        var id= $(this).attr('data-id');
        $.ajax({
           url: "{{route('store_daily_sales.save')}}",
           type: "POST",
           dataType: "json",
           data: $('.daily_sales_form_'+id).serialize(),
           success:function(response)
            {
                if(response.sal_id != ''){
                    $('.daily_sales_'+id).val(response.sal_id);
                }
            }
        });
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

</script>
