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
                    <h3 class="page-title">Selling Period</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Selling Period</li>
                    </ul>
                </div>
                
            </div>
        </div>    
        <!-- /Page Header -->
        <!-- Search Filter -->
        <form method="post" action="{{route('selling_period.list')}}" id="search_form">
            @csrf
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search company_drop select_change" name="company">
                            <option value="">Select Company</option>
                            <?php foreach ($company_list as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['company']) && $search['company']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Company</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="selectwith_search branch_drop select_change" name="branch">
                            <option value="">Select Branch</option>
                            <?php foreach ($branch_list as $key => $val) {?>
                                <option value="{{$key}}" <?php echo (isset($search['branch']) && $search['branch']==$key)?'selected':''; ?>>{{$val}}</option>
                            <?php } ?>
                        </select>
                        <label class="focus-label">Branch</label>
                    </div>
                </div>
                <div class="col-auto float-end ms-auto add_sell_btn" style="display:none">
                    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_Form"><i class="fa fa-plus"></i> Add Selling Period</a>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <table class="table table-striped custom-table mb-0 datatable stable">
                        <thead>
                            <tr>
                                <th width="30px">Company </th>
                                <th> Branch </th>
                                <th>Title </th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($selling_data) && count($selling_data) > 0)
                                @foreach($selling_data as $key => $val)
                                    <tr>
                                        <td>{{(isset($val->company_detail) && !empty($val->company_detail->name)) ? $val->company_detail->name : ''}}</td>
                                        <td>{{(isset($val->branch_detail) && !empty($val->branch_detail->name)) ? $val->branch_detail->name : ''}}</td>
                                        <td>{{$val->item_name}}</td>
                                        <td>
                                            <div class="pull-right">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" data-url="{{route('selling_period.statuschange',array($val->id,$val->is_show ?? 0))}}" id="flexSwitchCheckChecked" {{(!empty($val->is_show)) ? 'checked' : ''}}>
                                                </div>
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_Form" class="action-icon edit_branch" data-id="{{$val->id}}"><i class="fa fa-pencil"></i></a>
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_form" class="action-icon delete_branch" data-id="{{$val->id}}"><i class="fa fa-trash"></i></a>
                                                
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

    <!-- Add Selling Period Modal -->
    <div id="add_Form" class="modal custom-modal fade" role="dialog">
        @include('selling_management/selling_period_modal')
    </div>
    <!-- /Add Selling Period Modal -->
    
    <!-- Delete Selling Period Modal -->
    <div class="modal custom-modal fade" id="delete_form" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Selling Period</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                              <form action="{{route('selling_period.delete')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="selling_id" id="selling_delete_id">
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
    <!-- /Delete Selling Period Modal -->

</div>
<!-- /Page Wrapper -->


</div>


</body>


</html>

@include('includes/footer')
<script type="text/javascript">
    $(document).ready(function() {

        var com_id = $('.company_drop').val();
        var br_id = $('.branch_drop').val();
        $('#company_id').val(com_id);
        $('#branch_id').val(br_id);
        if(com_id != '' && br_id != ''){
            $('.add_sell_btn').show();
        }else{
            $('.add_sell_btn').hide();
        }

        $('.selectwith_search').select2({
            minimumResultsForSearch: 1,
            width: '100%'
        });
       
    });

    $(document).on('change','.select_change',function(){
        var com_id = $('.company_drop').val();
        var br_id = $('.branch_drop').val();
        $('#company_id').val(com_id);
        $('#branch_id').val(br_id);
        if(com_id != '' && br_id != ''){
            $('.add_sell_btn').show();
            $('#search_form').submit();
        }else{
            $('.add_sell_btn').hide();
        }
        
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

    $("#add_Form").on("hidden.bs.modal", function(){
        
        $("#is_bill_count").prop('checked',false);
        $('#item_name').val('');
        $('.leave_m_title').text('Create Selling Period');
    });
</script>
