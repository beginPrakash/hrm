<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<head>


    <style type="text/css">
    body {
    font-family: "CircularStd", sans-serif;
    font-size: 15px;
    }
   
        *{box-sizing:border-box;}
        .color-white{ color:white;}
        .color-2b5da7{ color: #2b5da7; }
        .color-0791bb{ color: #0791bb; }
        .color-5b9bd5{ color: #5b9bd5; }
        .color-bb0707{ color: #bb0707; }
        .bg-color-2b5da7 { background-color: #2b5da7; }
        .bg-color-0799c3 { background-color: #0799c3; }
        .bg-color-5b9bd5 { background-color: #5b9bd5; }
        img { max-width: 100%; }
        .text-center{text-align:center;}
        .text-right{text-align:right;}
        .text-left{text-align:left;}
        .mb-0 {margin-bottom: 0 !important;}


        /************************************ */
        .logo-wrap{text-align:center;}
        table{width:100%;border-collapse:collapse;}
        table tr th,
        table tr td {padding:5px 10px;text-align:left;}
        .custom-table th {padding: 5px 10px; font-size: 16px; }
        .custom-table tr {border-bottom: 1px solid #dee2e6;}
        .custom-table h3 {font-size: 14px;line-height: 16px; margin-bottom: 0; }

        .table-bordered{border:1px solid #dee2e6;}
        .table-bordered th,
        .table-bordered td{padding:3px 10px; border:1px solid #dee2e6;}
        .card{ position:relative;margin-top:25px;margin-bottom:25px;padding:20px;border-radius:25px;border:2px solid #0C0F1E;}
    </style>
</head>
<body>
    <div style="display: block; margin:0 auto;">
    <?php if(isset($emp_branch_data) && count($emp_branch_data) > 0): ?>
        <?php $__currentLoopData = $emp_branch_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bkey => $bval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <table style="margin-bottom: 30px;max-width:400px;">
                <tbody>
                    
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Branch: </b><?php echo e($bval->branch_name); ?>

                            </p>
                        </th>
                    </tr>
                </tbody>
            </table>
             
            <table class="custom-table" style="margin-bottom: 30px;max-width:400px;">
                <thead style="background-color: #F5F5F5">
                    <tr>
                        <th>S.no</th>
                        <th>Emp.Id</th>
                        <th>Name</th>
                        <th>Salary</th>
                        <th>Food Allowance</th>
                        <th>Addition of all other Allowances</th>
                        <th>Deduction</th>
                        <th>Total Earning</th>
                    </tr>
                </thead>
                <tbody>
                            
                <?php if(isset($emp_salary_data) && count($emp_salary_data) > 0): ?>
                    <?php $i = 1; ?>
                    
                    <?php $__currentLoopData = $emp_salary_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $total_allowence = total_allowence_withput_food($val->id); ?>
                        <?php if($val->branch_name == $bval->branch_name): ?>
                            <tr>
                                <td>
                                    <h3 class="mb-0"><?php echo e($i); ?></h3>
                                </td>
                                <td>
                                    <p><?php echo e($val->employee_id); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->name); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e(number_format($val->salary, 2)); ?></p> 
                                </td>
                                <td>
                                    <p><?php echo e($val->food_allowence ?? 0); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($total_allowence ?? 0); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->deduction); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e(number_format($val->total_earning, 2)); ?></p>
                                </td>
                            </tr>
                            <?php $i++; ?>    
                         <?php endif; ?>  
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
               
                        </tbody>
            </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
        
        <?php $currentMonth = date('F', mktime(0, 0, 0, $month, 10)); ?>
        <div style="font-size: 20px; margin-bottom:10px; font-weight: bold;">Summary of <?php echo e($currentMonth); ?> <?php echo e($year); ?></div>

        <table class="table table-bordered custom-table">
            <tbody> 
                <tr>
                    <td>Branch</td>
                    <?php if(isset($emp_company_data) && count($emp_company_data) > 0): ?>
                        <?php $__currentLoopData = $emp_company_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey => $cval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td colspan="2"><?php echo e($cval->company_name); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <?php if(isset($emp_company_data) && count($emp_company_data) > 0): ?>
                        <?php $__currentLoopData = $emp_company_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey => $cval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td>Cash</td>
                            <td>Bank</td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <td>Total</td>
                </tr>
                    
                <?php if(isset($emp_branch_data) && count($emp_branch_data) > 0): ?>
                    <?php $__currentLoopData = $emp_branch_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bkey => $bval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($bval->branch_name); ?></td>
                            <?php if(isset($emp_company_data) && count($emp_company_data) > 0): ?>
                                <?php $__currentLoopData = $emp_company_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey => $cval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td><?php echo e(number_format(calcualte_total_earning_by_month_company($month,$year,$cval->company_id,$bval->id,'cash'), 2)); ?></td>
                                    <td><?php echo e(calcualte_total_earning_by_month_company($month,$year,$cval->company_id,$bval->id,'bank')); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <td><?php echo e(number_format(calcualte_total_earning_by_month_company($month,$year,$cval->company_id,$bval->id,'','total'), 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                <tr>
                    <td>Total</td>
                    <?php if(isset($emp_company_data) && count($emp_company_data) > 0): ?>
                        <?php $__currentLoopData = $emp_company_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey => $cval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td><?php echo e(number_format(calcualte_total_by_month_company($month,$year,$cval->company_id,'cash'), 2)); ?></td>
                            <td><?php echo e(number_format(calcualte_total_by_month_company($month,$year,$cval->company_id,'bank'), 2)); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <table style="margin-top: 20px;">
                <tbody>
                <tr>
                    <td><b>HR Manager Sign.</b></td>
                    <td><b>Sr. Accountant Sign.</b></td>
                    <td><b>Finanace Manager Sign.</b></td>
                    <td><b>General Manager Sign.</b></td>
                    <td><b>CEO Sign.</b></td>
                </tr>
                </tbody>
        </table>


    <?php endif; ?>
    </div>
</body>
</html>
<?php //exit; ?><?php /**PATH C:\wamp64_new\www\hrm\resources\views/payroll/employee_salary_pdf.blade.php ENDPATH**/ ?>