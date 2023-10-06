@include('includes/header')
@include('includes/sidebar')

<!-- Page Wrapper -->
<div class="page-wrapper">
            
            <!-- Page Content -->
            <div class="content container-fluid">
                @include('flash-message') 
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title"><?php echo ucfirst($title); ?></h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active"><?php echo ucfirst($title); ?></li>
                            </ul>
                        </div>
                        <?php if(!isset($breadButton)){ ?>
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_Form"><i class="fa fa-plus"></i> Add <?php echo ucfirst($title); ?></a>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#import_shift"><i class="fa fa-plus"></i> Import <?php echo ucfirst($title); ?></a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <!-- Search Filter -->
                <form method="post" action="/employee">
                    @csrf
                    <div class="row filter-row">
                        <div class="col-sm-6 col-md-3">  
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating" name="employee_id" value="<?php echo (isset($search['employee_id']))?$search['employee_id']:''; ?>">
                                <label class="focus-label">Employee ID</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">  
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating" name="employee" value="<?php echo (isset($search['employee']))?$search['employee']:''; ?>">
                                <label class="focus-label">Employee Name</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3"> 
                            <div class="form-group form-focus select-focus">
                                <select class="select floating" name="designation"> 
                                    <option value="">Select Job title</option>
                                    <?php
                                    foreach ($designations as $designation) {?>
                                        <option  value="<?=$designation->id?>" <?php echo (isset($search['designation'])&&$search['designation']==$designation->id)?'selected':''; ?>><?=$designation->name?></option>
                                         <?php  } ?>   
                                </select>
                                <label class="focus-label">Job title</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3"> 
                            <div class="form-group form-focus select-focus">
                                <select class="select floating" name="branch"> 
                                    <option value="">Select Branch</option>
                                    <?php
                                    foreach ($branches as $branch) {?>
                                        <option  value="<?=$branch->id?>" <?php echo (isset($search['branch'])&&$search['branch']==$branch->id)?'selected':''; ?>><?=$branch->name?></option>
                                         <?php  } ?>   
                                </select>
                                <label class="focus-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success w-100"> Search </button>  
                            </div>  
                        </div>
                    </div>
                </form>
                <!-- Search Filter -->

                <div class="row staff-grid-row pt-4">
                    @if(isset($employees) && count($employees) > 0)
                        @foreach ($employees as $employee)
                        <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3 m-ht-3">
                            <div class="profile-widget">
                                <div class="profile-img">
                                    <a href="{{'/employeeProfileUpdate?id='.$employee->id }}" class="avatar">
                                        <img src="{{ ($employee->profile!=null)?'uploads/profile/'.$employee->profile:'assets/img/profiles/avatar.png'}}" alt=""></a>
                                </div>
                                <div class="dropdown profile-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{'/employeeProfileUpdate?id='.$employee->id }}" ><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_employee" data-data="{{$employee->id}}"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>
                                    </div>
                                </div>
                                <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="{{'/employeeProfileUpdate?id='.$employee->id }}">{{ ucfirst($employee->first_name) }}<small>({{$employee->emp_generated_id}})</small></a>
                                <?php echo ($employee->status=='resigned')?'<span class="badge bg-inverse-danger">Deactivated</span>':'<span class="badge bg-inverse-success">Active</span>'; ?></h4>
                                <div class="small text-muted"><?php echo ($employee->designation==3 && isset($employee->employee_department->name))?$employee->employee_department->name.' ':''; ?>
                                {{ $employee->employee_designation?ucfirst($employee->employee_designation->name):''}}
                                <br>
                                <?php echo (isset($employee->employee_branch))?$employee->employee_branch->name:''; ?>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 py-5 text-center">No employee found</div>
                    @endif
                </div>
                    
            
            <!-- /Page Content -->
            
            <!-- Add Employee Modal -->
            <div id="add_Form" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Employee</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form  action="/employeeInsert" method="post" id="addemployee">
                            @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                            <input class="form-control generate_uname" type="text" name="first_name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Last Name</label>
                                            <input class="form-control" type="text" name="last_name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Username <span class="text-danger">*</span></label>
                                            <input class="form-control username"  type="text" name="user_name" id="user_name">
                                        </div>
                                     </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                            <input class="form-control" type="email" name="email" id="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Password <span class="text-danger">*</span></label>
                                            <input class="form-control" type="password" name="password" id="password">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Confirm Password</label>
                                            <input class="form-control" type="password" name="conf_password">
                                        </div>
                                    </div>
                                   <div class="col-sm-6">  
                                        <div class="form-group">
                                            <label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="emp_generated_id" id="emp_generated_id" value="<?php echo $auto_id;?>">
                                        </div>
                                           
                                    </div>
                                    <div class="col-sm-6">  
                                        <div class="form-group">
                                            <label class="col-form-label">Joining Date <span class="text-danger">*</span></label>
                                            <div class="cal-icon"><input class="form-control datetimepicker" type="text" name="joining_date"></div>
                                        </div>
                                        
                                    </div>
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Passport No. </label>
                                            <input class="form-control" type="text" name="passport_no">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Passport Expiry </label>
                                            <div class="cal-icon"><input class="form-control datetimepicker" type="text" name="pass_expiry"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Address</label>
                                            <input class="form-control" type="text" name="local_address">
                                        </div>
                                    </div>
                                    
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Phone </label>
                                            <input class="form-control" type="text" name="phone">
                                        </div>
                                    </div>
                                    
                                    
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Visa No. </label>
                                            <input class="form-control" type="text" name="visa_no">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job title <span class="text-danger">*</span></label>
                                            <select class="select" name="designation" id="designation">
                                                <option value="">Select Job title</option>
                                                <?php foreach ($designationsWEmpCount as $designationEEC) {?>
                                                    <option data-id="<?=$designationEEC->multi_user?>" data-priority="<?=$designationEEC->priority_level?>" value="<?=$designationEEC->id?>" <?php echo($designationEEC->multi_user==0 && $designationEEC->employees_count == 1)?'disabled':''; ?>><?=$designationEEC->name?></option>
                                                <?php  } ?> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="department_div">
                                            <label>Department <span class="text-danger">*</span></label>
                                            <select class="form-control select" name="department" id="department">
                                                <!-- <option value="">Select Department</option> -->
                                                <?php foreach ($departments as $department) {?>
                                                    <option  value="<?=$department->id?>"><?=$department->name?></option>
                                                <?php  } ?>
                                            </select>
                                            <input type="hidden" name="dep_hid" id="dep_hid" value="0">
                                         </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group" id="company_div">
                                            <label class="col-form-label">Company <span class="text-danger">*</span></label>
                                            <select class="select" name="company" id="company"><option value="">Select Company</option>
                                                <?php
                                                foreach ($companies as $company) {?>
                                                    <option  value="<?=$company->id?>"><?=$company->name?></option>
                                                     <?php  } ?> 
                                            </select>
                                            <input type="hidden" name="com_hid" id="com_hid" value="0">
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
            <!-- /Add Employee Modal -->
            
            
            
            <!-- Delete Employee Modal -->
            <div class="modal custom-modal fade" id="delete_employee" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Employee</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <form method="post" action="/employeeDelete">
                                    @csrf
                                    <div class="row">
                                        <div class="col-6">
                                                <input type="hidden" name="employee_id" id="employee_id" value="">
                                                <button type="submit" class="btn btn-primary continue-btn col-12">Delete</button>
                                        </div>
                                        <div class="col-6">
                                            <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Delete Employee Modal -->

            <!-- Import Employee Modal -->
            <div class="modal custom-modal fade" id="import_shift" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Import Employee</h3>
                            </div>
                            <div class="modal-btn import-action">
                                <div class="row">
                                    <div class="col-12">
                                        <form action="/employeeImport" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label>Import File <span class="text-danger">*</span></label>
                                                <input class="form-control" value="" readonly type="file" name="employee_file">
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
            <!-- /Import Employee Modal -->
            
        </div>
        <!-- /Page Wrapper -->


</div>
<!-- end main wrapper-->


</body>


</html>

@include('includes/footer')
<script type="text/javascript">
    $(document).ready(function() {
        $("#addemployee").validate({
            rules: {
                first_name: 'required',
                password: 'required',
                // email: 'required',
                company: 'required',
                joining_date: 'required',
                // department: 'required',
                designation: 'required',
                conf_password: {
	            required: true,
                equalTo: "#password"},
                user_name: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "{{ route('isUsernameExists') }}",
                        data :{
                            "_token": "{{ csrf_token() }}",
                            'branch': function () {
                                return $('#user_name').val();
                            },
                        }
                    }
                },
                
                email: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "{{ route('isEmailExists') }}",
                        data :{
                            "_token": "{{ csrf_token() }}",
                            'branch': function () {
                                return $('#email').val();
                            },
                        }
                    }
                },
                
                emp_generated_id: {
                    required : true,
                    remote: {
                        type: 'post',
                        url: "{{ route('isEmployeeIdExists') }}",
                        data :{
                            "_token": "{{ csrf_token() }}",
                            'branch': function () {
                                return $('#emp_generated_id').val();
                            },
                        }
                    }
                }
             },
             
            messages: {
                first_name: 'First Name is required',
                password: 'Password is required', 
                // email: 'Email is required', 
                company: 'Choose Company',
                joining_date: 'Joining Date is required',
                // department: 'Department name is required',
                designation: 'Designation is required',
                conf_password: {
			   		required : 'Confirm Password is required',
			   		equalTo : 'Password not matching',
			   	},
                user_name: {
                    required : 'Username name is required',
                    remote: 'Username already exists'
                }  ,
                email: {
                    required : 'Email name is required',
                    remote: 'Email already exists'
                } ,
                emp_generated_id: {
                    required : 'Employee ID is required',
                    remote: 'Employee ID already exists'
                }                 
                
            },
            
       });
       
    });
</script>

<script>
    $(document).on('click','.deleteButton',function(){
        var id = $(this).data('data');
        // var decodedDataDelete = atob(rowDataDelete);
        // console.log(decodedDataDelete);
        // $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#employee_id').val(id);
        // });
    });
</script>
<script>
    $(document).on('blur', '.generate_uname', function(){
        var first_name = $(this).val();
        var rand       =  Math.floor(1000 + Math.random() * 9000);
        var username   = first_name + '@' +rand;
        $('.username').val(username);
    });
</script>