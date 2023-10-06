<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<head>

    <title>Shift List - HRMS admin template</title>
    <style>
        input[type="time"]::-webkit-calendar-picker-indicator {
            background: none !important;
        }
    </style>
    
</head>

<body>
    <div class="main-wrapper">
    

    <!-- Page Wrapper -->
            <div class="page-wrapper">
            
                <!-- Page Content -->
                <div class="content container-fluid">
                    <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>      
                    
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">Shift List</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="#">Employees</a></li>
                                    <li class="breadcrumb-item active">Shift List</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <a href="#" class="btn add-btn m-r-5" data-bs-toggle="modal" data-bs-target="#add_shift">Add Shifts</a>
                                <a href="#" class="btn add-btn m-r-5" data-bs-toggle="modal" data-bs-target="#import_shift"> Import Shifts</a>
                                <a href="/scheduling" class="btn add-btn m-r-5"> Assign Shifts</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    
                    <!-- Content Starts -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table datatablex" id="dt_table_shifts">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Shift Name</th>
                                            <th>Min Start Time</th>
                                            <th>Start Time</th>
                                            <th>Max Start Time</th>
                                            <th>Min End Time</th>
                                            <th>End Time</th>
                                            <th>Max End Time</th>
                                            <th>Break Time</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Content End -->
                    
                </div>
                <!-- /Page Content -->
                
            </div>
            <!-- /Page Wrapper -->
                
            <!-- Add Shift Modal -->
            <div id="add_shift" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Shift</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form  action="/shiftInsert" method="post" id="addForm">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Shift Name <span class="text-danger">*</span></label>
                                            <!-- <div class="input-group"> -->
                                                <input class="form-control" type="text" name="shift_name" onkeypress="return /[^/]/i.test(event.key)">
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            <label>Min Start Time <span class="text-danger">*</span></label>
                                            <div class="input-group timex timepickerx">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                <input class="form-control" type="time" name="min_start_time" id="add_min_start_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Start Time <span class="text-danger">*</span></label>
                                            <div class="input-group timex timepickerx">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                <input class="form-control" type="time" name="start_time" style="width: 83%;" id="add_start_time">
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max Start Time <span class="text-danger">*</span></label>
                                            <div class="input-group timex timepickerx">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                <input class="form-control" type="time" name="max_start_time" id="add_max_start_time">
                                            </div>                                          
                                        </div>
                                    </div>      
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            <label>Min End Time <span class="text-danger">*</span></label>
                                            <div class="input-group timex timepickerx">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                <input class="form-control" type="time" name="min_end_time" id="add_min_end_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>End Time <span class="text-danger">*</span></label>
                                            <div class="input-group timex timepickerx">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                <input class="form-control" type="time" name="end_time" style="width: 83%;" id="add_end_time">
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max End Time <span class="text-danger">*</span></label>
                                            <div class="input-group timex timepickerx">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                <input class="form-control" type="time" name="max_end_time" id="add_max_end_time">
                                            </div>                                          
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Break Time (In Minutes) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="break_time">                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="custom-control form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck1" name="recurring_shift" value="1">
                                            <label class="form-check-label" name="recurring_shift" for="customCheck1">Recurring Shift</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Repeat Every</label>
                                            <select class="select" name="repeat_every">
                                                <option value="">1</option>
                                                <option value="1">2</option>
                                                <option value="2">3</option>
                                                <option value="3">4</option>
                                                <option  selected value="4">5</option>
                                                <option value="3">6</option>
                                            </select>
                                            <label class="col-form-label">Week(s)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group wday-box">
                                            <label class="checkbox-inline"><input type="checkbox" value="monday" name="week_day[]" class="days recurring" checked=""><span class="checkmark">M</span></label>
        
                                            <label class="checkbox-inline"><input type="checkbox" value="tuesday"  name="week_day[]" class="days recurring" checked=""><span class="checkmark">T</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="wednesday"  name="week_day[]" class="days recurring" checked=""><span class="checkmark">W</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="thursday"  name="week_day[]" class="days recurring" checked=""><span class="checkmark">T</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="friday"  name="week_day[]" class="days recurring" checked=""><span class="checkmark">F</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="saturday"  name="week_day[]" class="days recurring"><span class="checkmark">S</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="sunday"  name="week_day[]" class="days recurring"><span class="checkmark">S</span></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">End On <span class="text-danger">*</span></label>
                                            <div class="cal-icon"><input class="form-control datetimepicker" type="text" name="end_on"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="custom-control form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck2" name="indefinite" value="1">
                                            <label class="form-check-label" for="customCheck2">Indefinite</label>
                                        </div>
                                    </div>                              
                            
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Add Tag </label>
                                            <input type="text" class="form-control" name="tag">                                            
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Add Note </label>
                                            <textarea class="form-control" name="note"></textarea>                                          
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Add Shift Modal -->

            <!-- Edit Shift Modal -->
            <div id="edit_shift" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Shift</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="/shiftUpdate" method="post" id="editForm">
                                <?php echo csrf_field(); ?>
                                <input class="form-control" value="" id="id" name="id" type="hidden">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Shift Name <span class="text-danger">*</span></label>
                                            <div class="input-groupx time timepicker">
                                                <input class="form-control" id="shift_name" name="shift_name" onkeypress="return /[^/]/i.test(event.key)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            <label>Min Start Time <span class="text-danger">*</span></label>
                                            <div class="input-group time timepicker">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span><input type="time" class="form-control" id="edit_min_start_time" name="min_start_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Start Time <span class="text-danger">*</span></label>
                                            <div class="input-group time timepicker">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span><input class="form-control" type="time" name="start_time" id="edit_start_time" style="width: 83%;">
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max Start Time <span class="text-danger">*</span></label>
                                            <div class="input-group time timepicker">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span><input class="form-control" type="time" name="max_start_time" id="edit_max_start_time">
                                            </div>                                          
                                        </div>
                                    </div>      
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            <label>Min End Time <span class="text-danger">*</span></label>
                                            <div class="input-group time timepicker">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span><input class="form-control" type="time" name="min_end_time" id="edit_min_end_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>End Time <span class="text-danger">*</span></label>
                                            <div class="input-group time timepicker">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span><input class="form-control" type="time" name="end_time" id="edit_end_time" style="width: 83%;">
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max End Time <span class="text-danger">*</span></label>
                                            <div class="input-group time timepicker">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span><input class="form-control" type="time" name="max_end_time" id="edit_max_end_time">
                                            </div>                                          
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Break Time (In Minutes) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="break_time">                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="custom-control form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck3">
                                            <label class="form-check-label" for="customCheck3">Recurring Shift</label>
                                            </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Repeat Every</label>
                                            <select class="select">
                                                <option value="">1 </option>
                                                <option value="1">2</option>
                                                <option value="2">3</option>
                                                <option value="3">4</option>
                                                <option  selected value="4">5</option>
                                                <option value="3">6</option>
                                            </select>
                                            <label class="col-form-label">Week(s)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group wday-box">
                                            <label class="checkbox-inline"><input type="checkbox" value="monday" class="days recurring" checked="" name="week_day[]"><span class="checkmark">M</span></label>
        
                                            <label class="checkbox-inline"><input type="checkbox" value="tuesday" class="days recurring" checked="" name="week_day[]"><span class="checkmark">T</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="wednesday" class="days recurring" checked="" name="week_day[]"><span class="checkmark">W</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="thursday" class="days recurring" checked="" name="week_day[]"><span class="checkmark">T</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="friday" class="days recurring" checked="" name="week_day[]"><span class="checkmark">F</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="saturday" class="days recurring" name="week_day[]"><span class="checkmark">S</span></label>
                                        
                                            <label class="checkbox-inline"><input type="checkbox" value="sunday" class="days recurring" name="week_day[]"><span class="checkmark">S</span></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">End On <span class="text-danger">*</span></label>
                                            <div class="cal-icon"><input class="form-control datetimepicker" type="text" name="end_on"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="custom-control form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck4" name="indefinite">
                                            <label class="form-check-label" for="customCheck4">Indefinite</label>
                                            </div>
                                    </div>                              
                            
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Add Tag </label>
                                            <input type="text" class="form-control" name="tag">                                            
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Add Note </label>
                                            <textarea class="form-control" name="note"></textarea>                                          
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Edit Shift Modal -->
                
            <!-- Delete Shift Modal -->
            <div class="modal custom-modal fade" id="delete_shift" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Shift</h3>
                                <p>Are you sure to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <form action="/shiftDelete" method="post">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="shift_delete_id" id="shift_delete_id" value="">
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
            <!-- /Delete shift Modal -->

            <!-- Import Shift Modal -->
            <div class="modal custom-modal fade" id="import_shift" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Import Shift</h3>
                            </div>
                            <div class="modal-btn import-action">
                                <div class="row">
                                    <div class="col-12">
                                        <form action="/shiftImport" method="post" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <div class="form-group">
                                                <label>Import File <span class="text-danger">*</span></label>
                                                <input class="form-control" value="" readonly type="file" name="shift_file">
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
            <!-- /Import shift Modal -->

</div>
<!-- end main wrapper-->


</body>

</html>
<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        var i = 1;
        var table_table = $('#dt_table_shifts').DataTable({
            responsive: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(route('shifting')); ?>",
            },

            columns: [
                {
                    "render": function() {
                        return i++;
                    }
                },
                {
                    data: 'shift_name',
                    name: 'shift_name',

                },
                {
                    data: 'min_start_time',
                    name: 'min_start_time',

                },
                {
                    data: 'start_time',
                    name: 'start_time',

                },
                {
                    data: 'max_start_time',
                    name: 'max_start_time',

                },
                {
                    data: 'min_end_time',
                    name: 'min_end_time',

                },
                {
                    data: 'end_time',
                    name: 'end_time',

                },
                {
                    data: 'max_end_time',
                    name: 'max_end_time',

                },
                {
                    data: 'break_time',
                    name: 'break_time',

                },
                {
                    data: 'status',
                    name: 'status',

                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        
        $("#addForm").validate({
            rules: {
                shift_name: {
                    required : true
                },
                min_start_time:{
                    required : true
                },
                start_time:{
                    required : true,
                    greaterThan: "#add_min_start_time"
                },
                max_start_time:{
                    required : true,
                    greaterThan: "#add_start_time"
                },
                min_end_time:{
                    required : true,
                    // greaterThan: "#add_max_start_time"
                },
                end_time:{
                    required : true,
                    greaterThan: "#add_min_end_time"
                },
                max_end_time:{
                    required : true,
                    greaterThan: "#add_end_time"
                },
                end_on:{
                    required : true
                },
                break_time:{
                    required : true
                }
            },
            messages: {
                shift_name: {
                    required : 'Shift Name is required'
                },
                min_start_time:{
                    required : 'Min Start Time is required'
                },
                start_time:{
                    required : 'Start Time is required',
                    greaterThan: 'Must be greater than Min Start time'
                },
                max_start_time:{
                    required : 'Max Start Time is required',
                    greaterThan: 'Must be greater than Start time'
                },
                min_end_time:{
                    required : 'Min End Time is required',
                    // greaterThan: 'Must be greater than Max Start time'
                },
                end_time:{
                    required : 'End Time is required',
                    greaterThan: 'Must be greater than Min End time'
                },
                max_end_time:{
                    required : 'Max End Time is required',
                    greaterThan: 'Must be greater than End time'
                },
                end_on:{
                    required : 'End On  is required'
                },
                break_time:{
                    required : 'Break time is required'
                }
            }
       });
       
    });
</script>
<script type="text/javascript">    
    jQuery.validator.addMethod("greaterThan", function(value, element, param) {
      var startTime = $(param).val();
      if (!value || !startTime) {
        return true; // Skip validation if either field is empty
      }
      var startMinutes = convertToMinutes(startTime);
      var endMinutes = convertToMinutes(value);
      return endMinutes >= startMinutes;
    }, "End time must be greater than or equal to start time.");
</script>
<script type="text/javascript">
    // Helper function to convert time to minutes
    function convertToMinutes(time) {
      var parts = time.split(":");
      return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        
        $("#editForm").validate({
            rules: {
                shift_name: {
                    required : true
                },
                min_start_time:{
                    required : true
                },
                start_time:{
                    required : true,
                    greaterThan: "#edit_min_start_time"
                },
                max_start_time:{
                    required : true,
                    greaterThan: "#edit_start_time"
                },
                min_end_time:{
                    required : true,
                    // greaterThan: "#edit_max_start_time"
                },
                end_time:{
                    required : true,
                    greaterThan: "#edit_min_end_time"
                },
                max_end_time:{
                    required : true,
                    greaterThan: "#edit_end_time"
                },
                end_on:{
                    required : true
                },
                break_time:{
                    required : true
                }
            },
            messages: {
                shift_name: {
                    required : 'Shift Name is required'
                },
                min_start_time:{
                    required : 'Min Start Time is required'
                },
                start_time:{
                    required : 'Proper Start Time is required',
                    greaterThan: 'Must be greater than Min Start time'
                },
                max_start_time:{
                    required : 'Max Start Time is required',
                    greaterThan: 'Must be greater than Start time'
                },
                min_end_time:{
                    required : 'Min End Time is required',
                    // greaterThan: 'Must be greater than Max Start time'
                },
                end_time:{
                    required : 'Proper End Time is required',
                    greaterThan: 'Must be greater than Min End time'
                },
                max_end_time:{
                    required : 'Max End Time is required',
                    greaterThan: 'Must be greater than End time'
                },
                end_on:{
                    required : 'End On  is required'
                },
                break_time:{
                    required : 'Break time is required'
                }
            }
       });
       
    });
   
</script>

<script>
  function getData(id)
  {
    alert(id);
    $.ajax({
       url: '/getShiftbyId/'+id,
       type: "GET",
       dataType: "json",
       success:function(response)
       {
        console.log(response[0]['addschedule_shifting']['shift_name']);
       //   $('#shift_name').val(response[0]['id']);
      // $('#shift_name').val(response[0]['addschedule_shifting']['shift_name']);
      // $('#min_start_time').val(response[0]['min_start_time']);
    
     }
   });
    
  }
  
</script>

<script>
    $(document).on('click','.editButton',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        var termarray = ['min_start_time', 'start_time', 'max_start_time', 'min_end_time', 'end_time', 'max_end_time'];
        $.each(JSON.parse(decodedData), function(key,value){
            // console.log(key+'-'+value);
            if(jQuery.inArray(key, termarray) !== -1)
            {
                var convertedTime = convert12HourTo24Hour(value);
                // var sp = value.split(':');
                $('#editForm [name="'+key+'"]').val(convertedTime);//sp[0]+':'+sp[1]);
            }
            else
            {
                $('#editForm [name="'+key+'"]').val(value);
            }
        });
    })
</script>
<script>
    $(document).on('click','.deleteButton',function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        // console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#shift_delete_'+key).val(value);
        });
    })
</script>

<script type="text/javascript">
    function convert12HourTo24Hour(time12) {
        var [time, modifier] = time12.split(" ");
        var [hours, minutes] = time.split(":");

        if (hours === "12") {
          hours = "00";
        }

        if (modifier === "pm") {
          hours = parseInt(hours, 10) + 12;
        }

        return hours + ":" + minutes;
      }
</script><?php /**PATH /home/eqb1fxfgkdl8/public_html/hrm/resources/views/lts/shifting.blade.php ENDPATH**/ ?>