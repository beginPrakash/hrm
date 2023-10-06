@include('includes/header')
@include('includes/sidebar')

<div class="main-wrapper">
	<!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Overtime Payslip</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Overtime Payslip</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <div class="btn-group btn-group-sm">
                            <!-- <button class="btn btn-white">CSV</button> -->
                            <!-- <button class="btn btn-white">PDF</button> -->
                            <!-- <button class="btn btn-white"><i class="fa fa-print fa-lg"></i> Print</button> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                        	<?php 
                        	$month = sprintf("%02d",$salaryDetails->es_month);
                        	$monthName = date('M', strtotime($salaryDetails->es_year.'-'.$month.'-01')); 
// echo '<pre>';print_r($employees[0]->employee_residency);
                        	?>
                            <h4 class="payslip-title">Overtime Payslip for the month of <?php echo $monthName.' '. $salaryDetails->es_year; ?></h4>
                            <div class="row">
                                <div class="col-sm-6 m-b-20">
                                    <img src="<?php echo (isset($employees[0]->employee_residency->logo) && $employees[0]->employee_residency->logo!=NULL)?'../uploads/logo/'.$employees[0]->employee_residency->logo:""; ?>" class="inv-logo" alt="">
                                    <ul class="list-unstyled mb-0">
                                        <li><strong><?php echo (isset($employees[0]->employee_residency->name))?strtoupper($employees[0]->employee_residency->name):''; ?></strong></li>
                                        <li><?php echo (isset($employees[0]->employee_residency->address))?ucwords($employees[0]->employee_residency->address):''; ?></li>
                                        <li><?php echo (isset($employees[0]->employee_residency->city))?ucwords($employees[0]->employee_residency->city):''; ?><?php echo (isset($employees[0]->employee_residency->state))?', '.ucwords($employees[0]->employee_residency->state):''; ?></li>
                                    </ul>
                                </div>
                                <div class="col-sm-6 m-b-20">
                                    <div class="invoice-details">
                                        <h3 class="text-uppercase">Overtime Payslip #<?php echo str_pad($salaryDetails->id, 5, '0', STR_PAD_LEFT); ?></h3>
                                        <ul class="list-unstyled">
                                            <li>Salary Month: <span><?php echo $monthName.', '. $salaryDetails->es_year; ?></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 m-b-20">
                                    <ul class="list-unstyled">
                                        <li><h5 class="mb-0"><strong><?php echo (isset($employees[0]->first_name))?ucwords($employees[0]->first_name):''; ?> <?php echo (isset($employees[0]->last_name))?ucwords($employees[0]->last_name):''; ?></strong></h5></li>
                                        <li><span><?php echo (isset($employees[0]->employee_designation->name))?ucwords($employees[0]->employee_designation->name):''; ?></span></li>
                                        <li>Employee ID: <?php echo (isset($employees[0]->emp_generated_id))?$employees[0]->emp_generated_id:''; ?></li>
                                        <li>Joining Date: <?php echo (isset($employees[0]->joining_date))?date('d, M Y', strtotime($employees[0]->joining_date)):''; ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                            	<?php
                            	$additiondetails = App\Models\EmployeeSalaryHistory::where('entry_type_title', 'add')->where('ems_id', $salaryDetails->id)->get();
                            	$deductiondetails = App\Models\EmployeeSalaryHistory::where('entry_type_title', 'ded')->where('ems_id', $salaryDetails->id)->get();
                            	$loandetails = App\Models\EmployeeSalaryHistory::where('entry_type_title', 'loan')->where('ems_id', $salaryDetails->id)->get();

                            	?>
                                <div class="col-sm-12">
                                    <div>
                                        <h4 class="m-b-10"><strong>Earnings</strong></h4>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Hours Paid</strong> <span class="float-end"><?php echo $salaryDetails->total_work_overtime; ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Month Days</strong> <span class="float-end"><?php echo $salaryDetails->month_w_days; ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Basic Salary</strong> <span class="float-end"><?php echo $salaryDetails->month_salary; ?></span></td>
                                                </tr>
                                                <!-- <tr>
                                                    <td><strong>Other Allowance</strong> <span class="float-end">$55</span></td>
                                                </tr> -->
                                                <tr>
                                                    <td><strong>Total Earnings</strong> <span class="float-end"><strong><?php echo number_format($salaryDetails->total_overtime_salary,2); ?></strong></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <p><strong>Net Salary: KWD <?php echo number_format($salaryDetails->total_overtime_salary,2); ?></strong> (<?php echo ucwords(numberToWords($salaryDetails->total_overtime_salary)); ?>)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->


</div>
<!-- end main wrapper-->
@include('includes/footer')