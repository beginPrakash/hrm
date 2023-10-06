@include('includes/header')
@include('includes/sidebar')

<div class="main-wrapper">

    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid">
            @include('flash-message')   
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Payroll Items</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Payroll Items</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Page Tab -->
            <div class="page-menu">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab_additions">Additions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_overtime">Overtime</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_deductions">Deductions</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Tab -->
            
            <!-- Tab Content -->
            <div class="tab-content">
            
                <!-- Additions Tab -->
                <div class="tab-pane show active" id="tab_additions">
                
                    <!-- Add Addition Button -->
                    <div class="text-end mb-4 clearfix">
                        <button class="btn btn-primary add-btn" type="button" data-bs-toggle="modal" data-bs-target="#add_addition"><i class="fa fa-plus"></i> Add Addition</button>
                    </div>
                    <!-- /Add Addition Button -->

                    <!-- Payroll Additions Table -->
                    <div class="payroll-table card">
                        <div class="table-responsive">
                            <table class="table table-hover table-radius">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Default/Unit Amount</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(isset($additions))
                                    {
                                        foreach($additions as $adi)
                                        {
                                            $encodedDataAdi = base64_encode(json_encode($adi));
                                        ?>
                                        <tr>
                                            <th><?php echo $adi->name; ?></th>
                                            <td><?php echo ($adi->category==1)?'Monthly Remuneration':'Additional Remuneration'; ?></td>
                                            <td>$<?php echo $adi->unit_amount; ?></td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item editButtonAdi" href="#" data-bs-toggle="modal" data-bs-target="#edit_addition" data-data="<?php echo $encodedDataAdi; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item deleteButtonAdi" href="#" data-bs-toggle="modal" data-bs-target="#delete_addition" data-data="<?php echo $encodedDataAdi; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
                    <!-- /Payroll Additions Table -->
                    
                </div>
                <!-- Additions Tab -->
                
                <!-- Overtime Tab -->
                <div class="tab-pane" id="tab_overtime">
                
                    <!-- Add Overtime Button -->
                    <div class="text-end mb-4 clearfix">
                        <button class="btn btn-primary add-btn" type="button" data-bs-toggle="modal" data-bs-target="#add_overtime"><i class="fa fa-plus"></i> Add Overtime</button>
                    </div>
                    <!-- /Add Overtime Button -->

                    <!-- Payroll Overtime Table -->
                    <div class="payroll-table card">
                        <div class="table-responsive">
                            <table class="table table-hover table-radius">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Rate</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(isset($overtime))
                                    {
                                        foreach($overtime as $ovt)
                                        {
                                            $encodedDataOvt = base64_encode(json_encode($ovt));
                                        ?>
                                        <tr>
                                            <th><?php echo $ovt->name; ?></th>
                                            <td><?php echo ($ovt->rate_type==2)?'Hourly':'Daily'; ?> <?php echo $ovt->rate; ?></td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item editButtonOvt" href="#" data-bs-toggle="modal" data-bs-target="#edit_overtime" data-data="<?php echo $encodedDataOvt; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item deleteButtonOvt" href="#" data-bs-toggle="modal" data-bs-target="#delete_overtime" data-data="<?php echo $encodedDataOvt; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
                    <!-- /Payroll Overtime Table -->
                    
                </div>
                <!-- /Overtime Tab -->
                
                <!-- Deductions Tab -->
                <div class="tab-pane" id="tab_deductions">
                
                    <!-- Add Deductions Button -->
                    <div class="text-end mb-4 clearfix">
                        <button class="btn btn-primary add-btn" type="button" data-bs-toggle="modal" data-bs-target="#add_deduction"><i class="fa fa-plus"></i> Add Deduction</button>
                    </div>
                    <!-- /Add Deductions Button -->

                    <!-- Payroll Deduction Table -->
                    <div class="payroll-table card">
                        <div class="table-responsive">
                            <table class="table table-hover table-radius">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Default/Unit Amount</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(isset($deductions))
                                    {
                                        foreach($deductions as $ded)
                                        {
                                            $encodedDataDed = base64_encode(json_encode($ded));
                                        ?>
                                        <tr>
                                            <th><?php echo $ded->name; ?></th>
                                            <td>$<?php echo $ded->unit_amount; ?></td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item editButtonDed" href="#" data-bs-toggle="modal" data-bs-target="#edit_deduction" data-data="<?php echo $encodedDataDed; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item deleteButtonDed" href="#" data-bs-toggle="modal" data-bs-target="#delete_deduction" data-data="<?php echo $encodedDataDed; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
                    <!-- /Payroll Deduction Table -->
                    
                </div>
                <!-- /Deductions Tab -->
                
            </div>
            <!-- Tab Content -->
            
        </div>
        <!-- /Page Content -->
        
        <!-- Add Addition Modal -->
        <div id="add_addition" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Addition</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/additionInsert" method="post">
                            @csrf
                            <input type="hidden" name="settings_type" value="addition">
                            
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Category <span class="text-danger">*</span></label>
                                <select class="select" name="category" required>
                                    <option value="">Select a category</option>
                                    <option value="1">Monthly remuneration</option>
                                    <option value="2">Additional remuneration</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Unit calculation</label>
                                <div class="status-toggle">
                                    <input type="checkbox" id="unit_calculation_adi" class="check" name="is_unit" value="1" required>
                                    <label for="unit_calculation_adi" class="checktoggle">checkbox</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Unit Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control" name="unit_amount" required>
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Assignee</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="deduction_assignee" id="deduction_no_emp" value="no" checked>
                                    <label class="form-check-label" for="deduction_no_emp">
                                    No assignee
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="deduction_assignee" id="deduction_all_emp" value="all">
                                    <label class="form-check-label" for="deduction_all_emp">
                                    All employees
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="deduction_assignee" id="deduction_single_emp" value="select">
                                    <label class="form-check-label" for="deduction_single_emp">
                                    Select Employee
                                    </label>
                                </div>
                                <div class="form-group">
                                    <select class="select" multiple name="employees[]">
                                        <option value="">-</option>
                                        <option value="0">Select All</option>
                                        <?php
                                        if(isset($employees))
                                        {
                                            foreach($employees as $emp)
                                            {
                                            ?>
                                            <option value="<?php echo $emp->user_id; ?>"><?php echo $emp->first_name; ?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
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
        <!-- /Add Addition Modal -->
        
        <!-- Edit Addition Modal -->
        <div id="edit_addition" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Addition</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/additionUpdate" method="post" id="editForm">
                            @csrf
                            <input type="hidden" name="settings_type" value="addition">
                            <input type="hidden" name="adi_id" id="adi_id" value="">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="adi_name">
                            </div>
                            <div class="form-group">
                                <label>Category <span class="text-danger">*</span></label>
                                <select class="select" name="category" id="adi_category">
                                    <option value="">Select a category</option>
                                    <option value="1">Monthly remuneration</option>
                                    <option value="2">Additional remuneration</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Unit calculation</label>
                                <div class="status-toggle">
                                    <input type="checkbox" id="adi_is_unit" class="check" name="is_unit">
                                    <label for="adi_is_unit" class="checktoggle">checkbox</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Unit Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control" name="unit_amount" id="adi_edit_unit_amount">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Assignee</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_addition_assignee" id="edit_addition_no_emp" value="no" checked>
                                    <label class="form-check-label" for="edit_addition_no_emp">
                                    No assignee
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_addition_assignee" id="edit_addition_all_emp" value="all">
                                    <label class="form-check-label" for="edit_addition_all_emp">
                                    All employees
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_addition_assignee" id="edit_addition_single_emp" value="select">
                                    <label class="form-check-label" for="edit_addition_single_emp">
                                    Select Employee
                                    </label>
                                </div>
                                <div class="form-group">
                                    <select class="select">
                                        <option value="">-</option>
                                        <option value="0">Select All</option>
                                        <?php
                                        if(isset($employees))
                                        {
                                            foreach($employees as $emp)
                                            {
                                            ?>
                                            <option value="<?php echo $emp->user_id; ?>"><?php echo $emp->first_name; ?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Addition Modal -->
        
        <!-- Delete Addition Modal -->
        <div class="modal custom-modal fade" id="delete_addition" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Addition</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <form action="/additionDelete" method="post">
                                        @csrf
                                        <input type="hidden" name="adi_delete_id" id="adi_delete_id">
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
        <!-- /Delete Addition Modal -->
        
        <!-- Add Overtime Modal -->
        <div id="add_overtime" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Overtime</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/overtimeSettingsInsert" method="post">
                            @csrf
                            <input type="hidden" name="settings_type" value="overtime">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Rate Type <span class="text-danger">*</span></label>
                                <select class="select" name="rate_type" required>
                                    <option value="">-</option>
                                    <option value="1">Daily Rate</option>
                                    <option value="2">Hourly Rate</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Rate <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="rate" required>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Overtime Modal -->
        
        <!-- Edit Overtime Modal -->
        <div id="edit_overtime" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Overtime</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/overtimeSettingsUpdate" method="post" id="editForm">
                            @csrf
                            <input type="hidden" name="settings_type" value="overtime">
                            <input type="hidden" name="ovt_id" id="ovt_id" value="">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="ovt_name" required>
                            </div>
                            <div class="form-group">
                                <label>Rate Type <span class="text-danger">*</span></label>
                                <select class="select" name="rate_type" id="ovt_rate_type" required>
                                    <option value="">-</option>
                                    <option value="1">Daily Rate</option>
                                    <option value="2">Hourly Rate</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Rate <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="rate" id="ovt_rate" required>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Overtime Modal -->
        
        <!-- Delete Overtime Modal -->
        <div class="modal custom-modal fade" id="delete_overtime" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Overtime</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <form action="/overtimeSettingDelete" method="post">
                                        @csrf
                                        <input type="hidden" name="ovt_delete_id" id="ovt_delete_id">
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
        <!-- /Delete Overtime Modal -->
        
        <!-- Add Deduction Modal -->
        <div id="add_deduction" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Deduction</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/deductionInsert" method="post">
                            @csrf
                            <input type="hidden" name="settings_type" value="deductions">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" required>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Unit calculation</label>
                                <div class="status-toggle">
                                    <input type="checkbox" id="unit_calculation_deduction" class="check" value="0" name="is_unit">
                                    <label for="unit_calculation_deduction" class="checktoggle">checkbox</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Unit Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control" name="unit_amount" required>
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Assignee</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="deduction_assignee" id="deduction_no_emp" value="no" checked>
                                    <label class="form-check-label" for="deduction_no_emp">
                                    No assignee
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="deduction_assignee" id="deduction_all_emp" value="all">
                                    <label class="form-check-label" for="deduction_all_emp">
                                    All employees
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="deduction_assignee" id="deduction_single_emp" value="select">
                                    <label class="form-check-label" for="deduction_single_emp">
                                    Select Employee
                                    </label>
                                </div>
                                <div class="form-group">
                                    <select class="select" multiple name="employees[]">
                                        <option value="">-</option>
                                        <option value="0">Select All</option>
                                        <?php
                                        if(isset($employees))
                                        {
                                            foreach($employees as $emp)
                                            {
                                            ?>
                                            <option value="<?php echo $emp->user_id; ?>"><?php echo $emp->first_name; ?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
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
        <!-- /Add Deduction Modal -->
        
        <!-- Edit Deduction Modal -->
        <div id="edit_deduction" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Deduction</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/deductionUpdate" method="post" id="editForm">
                            @csrf
                            <input type="hidden" name="settings_type" value="deductions">
                            <input type="hidden" name="ded_id" id="ded_id" value="">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="ded_name">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Unit calculation</label>
                                <div class="status-toggle">
                                    <input type="checkbox" id="ded_is_unit" class="check" name="is_unit">
                                    <label for="edit_unit_calculation_deduction" class="checktoggle">checkbox</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Unit Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control" name="unit_amount" id="ded_unit_amount">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Assignee</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_deduction_assignee" id="edit_deduction_no_emp" value="no" checked>
                                    <label class="form-check-label" for="edit_deduction_no_emp">
                                    No assignee
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_deduction_assignee" id="edit_deduction_all_emp" value="all">
                                    <label class="form-check-label" for="edit_deduction_all_emp">
                                    All employees
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_deduction_assignee" id="edit_deduction_single_emp" value="select">
                                    <label class="form-check-label" for="edit_deduction_single_emp">
                                    Select Employee
                                    </label>
                                </div>
                                <div class="form-group">
                                    <select class="select">
                                        <option value="">-</option>
                                        <option value="0">Select All</option>
                                        <?php
                                        if(isset($employees))
                                        {
                                            foreach($employees as $emp)
                                            {
                                            ?>
                                            <option value="<?php echo $emp->user_id; ?>"><?php echo $emp->first_name; ?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Addition Modal -->
        
        <!-- Delete Deduction Modal -->
        <div class="modal custom-modal fade" id="delete_deduction" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Deduction</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <form action="/deductionDelete" method="post">
                                        @csrf
                                        <input type="hidden" name="ded_delete_id" id="ded_delete_id">
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
        <!-- /Delete Deduction Modal -->
        
    </div>
    <!-- /Page Wrapper -->


</div>
<!-- end main wrapper-->

@include('includes/footer')


<script>
    $(document).on('click','.editButtonAdi',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            $('#adi_'+key).val(value);
            if(key=='is_unit' && value==1)
            {
                $("#adi_is_unit").attr('checked', 'checked');
            }
            if(key=='category')
            {
                $("#adi_category").val(value).trigger('change');
            }
            if(key=='unit_amount')
            {
                $("#adi_edit_unit_amount").val(value);
            }
            if(key=='assignee_type')
            {
                if(value=='no')
                {
                    $("#edit_addition_no_emp").attr('checked', 'checked');
                }
                else if(value=='all')
                {
                    $("#edit_addition_all_emp").attr('checked', 'checked');
                }
                else if(value=='select')
                {
                    $("#edit_addition_single_emp").attr('checked', 'checked');
                }
            }
        });
    })
</script>
<script>
    $(document).on('click','.deleteButtonAdi', function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        // console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#adi_delete_'+key).val(value);
        });
    })
</script>

<script>
    $(document).on('click','.editButtonOvt',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            $('#ovt_'+key).val(value);
            if(key=='rate_type')
            {
                $("#ovt_rate_type").val(value).trigger('change');
            }
        });
    })
</script>
<script>
    $(document).on('click','.deleteButtonOvt', function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        // console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#ovt_delete_'+key).val(value);
        });
    })
</script>

<script>
    $(document).on('click','.editButtonDed',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            $('#ded_'+key).val(value);
            if(key=='is_unit' && value==1)
            {
                $("#ded_is_unit").attr('checked', 'checked');
            }
            if(key=='assignee_type')
            {
                if(value=='no')
                {
                    $("#edit_deduction_no_emp").attr('checked', 'checked');
                }
                else if(value=='all')
                {
                    $("#edit_deduction_all_emp").attr('checked', 'checked');
                }
                else if(value=='select')
                {
                    $("#edit_deduction_single_emp").attr('checked', 'checked');
                }
            }
        });
    })
</script>
<script>
    $(document).on('click','.deleteButtonDed', function(){
        var rowDataDelete = $(this).data('data');
        var decodedDataDelete = atob(rowDataDelete);
        // console.log(decodedDataDelete);
        $.each(JSON.parse(decodedDataDelete), function(key,value){
            $('#ded_delete_'+key).val(value);
        });
    })
</script>