@include('includes/header')
@include('includes/sidebar')


<?php $total_leave = 0; 
foreach ($leavetype as $value){
    $total_leave += $value->days;
}?>

 <!-- Page Wrapper -->
            <div class="page-wrapper">
            
                <!-- Page Content -->
                <div class="content container-fluid">
                
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Leaves</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Leaves</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <?php if(Session::get('is_admin')==0) { ?>
                                    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_leave"><i class="fa fa-plus"></i> Add Leave</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    
                    <!-- Leave Statistics -->
                    <div class="row">
<!--                         <div class="col-md-3">
                            <div class="stats-info">
                                <h6>Today Presents</h6>
                                <h4>12 / 60</h4>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <div class="stats-info">
                                <h6>Remaning Leaves</h6>
                                <h4>0 / <?php echo $total_leave?><span></span></h4>
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="stats-info">
                                <h6>Pending Requests</h6>
                                <h4><?php  print_r($total_pending_request);?></h4>
                            </div>
                        </div>
                    </div>
                    <!-- /Leave Statistics -->
                    
                    <!-- Search Filter -->
                    <form action="/leaves" method="post" id="search_form">
                        @csrf
                        <div class="row filter-row">
                           <!-- <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <div class="form-group form-focus">
                                    <input type="text" class="form-control floating">
                                    <label class="focus-label">Employee Name</label>
                                </div>
                           </div> -->
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <div class="form-group form-focus select-focus">
                                    <select class="select floating" name="leave_type"> 
                                        <option value=""> -- Select -- </option>
                                        <?php foreach ($leavetype as $value) {?>
                                            
                                        <option value="<?php echo $value->id?>" <?php echo (isset($where['leave_type']) && $where['leave_type']==$value->id)?'selected':''; ?>><?php echo $value->name?></option>
                                    <?php }?>
                                    </select>
                                    <label class="focus-label">Leave Type</label>
                                </div>
                           </div>
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12"> 
                                <div class="form-group form-focus select-focus">
                                    <select class="select floating" name="leave_status"> 
                                        <option value=""> -- Select -- </option>
                                        <option value="new" <?php echo (isset($where['leave_status']) && $where['leave_status']=='new')?'selected':''; ?>>New</option>
                                        <option value="pending" <?php echo (isset($where['leave_status']) && $where['leave_status']=='pending')?'selected':''; ?>>Pending</option>
                                        <option value="approved" <?php echo (isset($where['leave_status']) && $where['leave_status']=='approved')?'selected':''; ?>>Approved</option>
                                        <option value="declined" <?php echo (isset($where['leave_status']) && $where['leave_status']=='declined')?'selected':''; ?>>Rejected</option>
                                    </select>
                                    <label class="focus-label">Leave Status</label>
                                </div>
                           </div>
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <div class="form-group form-focus">
                                    <div class="cal-icon">
                                        <input class="form-control floating datetimepicker" type="text" name="leave_from" value="<?php echo (isset($where['leave_from']))?$where['leave_from']:''; ?>">
                                    </div>
                                    <label class="focus-label">From</label>
                                </div>
                            </div>
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <div class="form-group form-focus">
                                    <div class="cal-icon">
                                        <input class="form-control floating datetimepicker" type="text" name="leave_to" value="<?php echo (isset($where['leave_to']))?$where['leave_to']:''; ?>">
                                    </div>
                                    <label class="focus-label">To</label>
                                </div>
                            </div>
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <input type="submit" class="btn btn-success w-100" name="search" value="Search"> 
                           </div>     
                        </div>
                    </form>
                    <!-- /Search Filter -->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped  custom-table mb-0 " id="employee_leaves">
                                    <thead>
                                        <tr>
                                            <!-- <th>Employee</th> -->
                                            <th style="width: 30px;">#</th>
                                            <th>Leave Type</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>No of Days</th>
                                            <th>Reason</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($leaves))
                                        {
                                            $i = 0;
                                            foreach($leaves as $lv)
                                            {
                                                $i++;
                                            ?>
                                            <tr>
                                               <td>
                                                    <?php echo $i; ?>
                                                </td> 
                                                <td><?php echo ucfirst($lv->leaves_leavetype->name); ?></td>
                                                <td><?php echo ucfirst($lv->leave_from); ?></td>
                                                <td><?php echo ucfirst($lv->leave_to); ?></td>
                                                <td><?php echo ucfirst($lv->leave_days); ?> days</td>
                                                <td><?php echo ucfirst($lv->leave_reason); ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    if((isset($userdetails[0]->employee_designation)) && $userdetails[0]->employee_designation->priority_level == 0)
                                                    { 
                                                        echo ucfirst($lv->leave_status);
                                                    }  else { ?>
                                                    <div class="dropdown action-label">
                                                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-dot-circle-o text-purple"></i> New
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-purple"></i> New</a>
                                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-info"></i> Pending</a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#approve_leave"><i class="fa fa-dot-circle-o text-success"></i> Approved</a>
                                                            <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i> Declined</a>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-end">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Page Content -->
                
                <!-- Add Leave Modal -->
                <div id="add_leave" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Leave</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                              
                                    <form  action="/leaveInsert" method="post" id="addLeave">
                            @csrf
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select" name="leave_type">

                                           
                                             <?php foreach ($leavetype as $value) {?>

                                            <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                                        <?php }?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>From <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="from_date" value="<?=date('Y-m-d'); ?>" id="from_date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>To <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="to_date" id="to_date" value="<?=date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Number of days <span class="text-danger">*</span></label>
                                        <input class="form-control" value="1" readonly type="text" name="days">
                                    </div>
                                    <div class="form-group">
                                        <label>Remaining Leaves <span class="text-danger">*</span></label>
                                        <input class="form-control" readonly type="text" name="remaining_leaves" value="4">
                                    </div>
                                    <div class="form-group">
                                        <label>Leave Reason <span class="text-danger">*</span></label>
                                        <textarea rows="4" class="form-control" name="leave_reason"></textarea>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Add Leave Modal -->
                
                <!-- Edit Leave Modal -->
                <div id="edit_leave" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Leave</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select">
                                            <option>Select Leave Type</option>
                                            <option>Casual Leave 12 Days</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>From <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" value="01-01-2019" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>To <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" value="01-01-2019" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Number of days <span class="text-danger">*</span></label>
                                        <input class="form-control" readonly type="text" value="2">
                                    </div>
                                    <div class="form-group">
                                        <label>Remaining Leaves <span class="text-danger">*</span></label>
                                        <input class="form-control" readonly value="12" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label>Leave Reason <span class="text-danger">*</span></label>
                                        <textarea rows="4" class="form-control">Going to hospital</textarea>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Edit Leave Modal -->

                <!-- Approve Leave Modal -->
                <div class="modal custom-modal fade" id="approve_leave" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Leave Approve</h3>
                                    <p>Are you sure want to approve for this leave?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="javascript:void(0);" class="btn btn-primary continue-btn">Approve</a>
                                        </div>
                                        <div class="col-6">
                                            <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Decline</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Approve Leave Modal -->
                
                <!-- Delete Leave Modal -->
                <div class="modal custom-modal fade" id="delete_approve" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Delete Leave</h3>
                                    <p>Are you sure want to delete this leave?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
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
                <!-- /Delete Leave Modal -->
                
            </div>
            <!-- /Page Wrapper -->


@include('includes/footer')
<script type="text/javascript">
    $(document).ready(function() {
        
        $("#addLeave").validate({
            rules: {
                leave_type: {
                    required : true},
                from_date:  {
                    required : true},
                to_date:  {
                    required : true},
                days:  {
                    required : true},
                remaining_leaves:  {
                    required : true},

                remaining_leaves:  {
                    required : true},
                    leave_reason:  {
                    required : true},
            },
            messages: {
                leave_type: {
                    required : 'Leave Type is required',
                },
                 from_date: {
                    required : 'From Date is required',
                }
                ,
                to_date: {
                    required : 'To Date is required',
                },
                days: {
                    required : 'Days is required',
                },
                remaining_leaves: {
                    required : 'Remaining Leaves is required',
                },
                leave_reason: {
                    required : 'Leaves reason is required',
                }
            },
       });
       
    });
</script>



<script type="text/javascript">
  
// $(document).ready(function () {
//         var i = 1;
//         var table_table = $('#employee_leaves').DataTable({
//             responsive: true,
//             fixedHeader: {
//                 header: true,
//                 footer: true
//             },
//             processing: true,
//             serverSide: true,
//             ajax: {
//                 url: "{{ route('leaves') }}",
//                 method:'POST',
//                 "data": function(d){
//                     d.form = $("#search_form").serializeArray();
//                 },
//                 error:err=>{
//                     console.log(err)
//                     alert_toast("An error occured",'error')
//                     end_load()
//                 },
//             },

//             columns: [
//                 {
//                     "render": function() {
//                         return i++;
//                     }
//                 },
//                 {
//                     data: 'leave type',
//                     name: 'leave type',

//                 },
//                 {
//                     data: 'from',
//                     name: 'from',

//                 },
//                 {
//                     data: 'to',
//                     name: 'to',

//                 },
//                 {
//                     data: 'no of days',
//                     name: 'no of days',
//                 }
//                 ,
//                 {
//                     data: 'reason',
//                     name: 'reason',
//                 }
//                 ,
//                 {
//                     data: 'status',
//                     name: 'status',
//                 }
//                 ,
//                 {
//                     data: 'action',
//                     name: 'action',
//                     orderable: false
//                 }
//             ],
//         });
//     });

</script>


<script type="text/javascript">
  
    $(document).ready(function () {
        let currentDate = new Date().toJSON().slice(0, 10);
        console.log(currentDate); // "2022-06-17"
        $('#from_date').val(currentDate);
        $('#to_date').val(currentDate);
        });
    
    </script>




