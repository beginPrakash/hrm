<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="main-wrapper">
	<!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Payslip</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Payslip</li>
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
                            <h4 class="payslip-title">Payslip for the month of <?php echo $monthName.' '. $salaryDetails->es_year; ?></h4>
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
                                        <h3 class="text-uppercase">Payslip #<?php echo str_pad($salaryDetails->id, 5, '0', STR_PAD_LEFT); ?></h3>
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

                            	$col = 6;
                            	if(count($additiondetails) > 0 && count($deductiondetails) > 0 && count($loandetails) > 0)
                            	{
                            		$col = 4;
                            	}
                                $hrs = explode(':',$salaryDetails->total_work_hours);
                                $wdays = ceil($hrs[0]/$salaryDetails->day_hours);

                                $tearnings = ($salaryDetails->month_salary/$salaryDetails->month_w_days) * $wdays;
                            	?>
                                <div class="col-sm-<?php echo $col; ?>">
                                    <div>
                                        <h4 class="m-b-10"><strong>Earnings</strong></h4>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Hours Paid</strong> <span class="float-end"><?php echo $salaryDetails->total_work_hours; ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Worked Days</strong> <span class="float-end"><?php echo $wdays; ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Basic Salary</strong> <span class="float-end"><?php echo $salaryDetails->month_salary; ?></span></td>
                                                </tr>
                                                <!-- <tr>
                                                    <td><strong>Other Allowance</strong> <span class="float-end">$55</span></td>
                                                </tr> -->
                                                <tr>
                                                    <td><strong>Total Earnings</strong> <span class="float-end"><strong><?php echo number_format($tearnings,2); ?></strong></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-<?php echo $col; ?>">
                                    <div>
                                        <h4 class="m-b-10"><strong>Deductions</strong></h4>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Loan Deduction</strong> <span class="float-end"><?php echo (count($loandetails) > 0 ) ? $loandetails[0]->entry_value: 0; ?></span></td>
                                                </tr>
                                                <?php
                                                $totDed = (count($loandetails) > 0 ) ? $loandetails[0]->entry_value: 0;
                                                if(isset($deductiondetails))
                                                {
                                                    foreach($deductiondetails as $ded)
                                                    {
                                                        $totDed += $ded->entry_value;
                                                    ?>
                                                    <tr>
                                                        <td><strong><?php echo $ded->remarks; ?></strong> <span class="float-end"><?php echo $ded->entry_value; ?></span></td>
                                                    </tr>
                                                    <?php
                                                    }
                                                }?>
                                                <!-- <tr>
                                                    <td><strong>Provident Fund</strong> <span class="float-end">$0</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>ESI</strong> <span class="float-end">$0</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Loan</strong> <span class="float-end">$300</span></td>
                                                </tr> -->
                                                <tr>
                                                    <td><strong>Total Deductions</strong> <span class="float-end"><strong><?php echo $totDed; ?></strong></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php if(count($additiondetails) > 0){ ?>
                                <div class="col-sm-<?php echo $col; ?>">
                                    <div>
                                        <h4 class="m-b-10"><strong>Additions</strong></h4>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <?php
                                                $totAdd = 0;
                                                if(isset($additiondetails))
                                                {
                                                    foreach($additiondetails as $addi)
                                                    {
                                                        $totAdd += $addi->entry_value;
                                                    ?>
                                                    <tr>
                                                        <td><strong><?php echo $addi->remarks; ?></strong> <span class="float-end"><?php echo $addi->entry_value; ?></span></td>
                                                    </tr>
                                                    <?php
                                                    }
                                                }?>
                                                <tr>
                                                    <td><strong>Total Additions</strong> <span class="float-end"><strong><?php echo $totAdd; ?></strong></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                                <div class="col-sm-12">
                                    <p><strong>Net Salary: KWD <?php echo number_format($tearnings,2); ?></strong> (<?php echo ucwords(numberToWords($tearnings)); ?>)</p>
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
<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eqb1fxfgkdl8/public_html/hrm/resources/views/payroll/employee_salary_slip.blade.php ENDPATH**/ ?>