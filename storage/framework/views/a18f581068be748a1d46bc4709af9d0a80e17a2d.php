<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<head>
    <style type="text/css">
        @page{margin:50px 25px;}
        body,p {font-weight:normal;font-family: 'Sharp Grotesk';font-size:14px;color:#0C0F1E;line-height:20px;}

        th,b,strong{font-family: 'Sharp Grotesk Book';}
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
        .custom-table th {padding: 5px 10px; font-size: 16px; font-family:'Iskry Bold';}
        .custom-table tr {border-bottom: 1px solid #dee2e6;}
        .custom-table h3 {font-size: 14px;line-height: 16px; font-family:'Iskry Bold'; margin-bottom: 0; }

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
                    <?php if($bkey == 0): ?>
                    <tr>
                        <th colspan="2" class="text-center">
                            <a href="<?php echo e(url('/')); ?>">
                                <img src="<?php echo e(asset('assets/img/logo1.png')); ?>" style="width: 140px;">
                            </a>
                        </th>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th width="80px" style="vertical-align:top">                        
                            <p class="text-left mb-0">
                                <b>Branch :</b><?php echo e($bval->branch_name); ?>

                            </p>
                        </th>
                    </tr>
                </tbody>
            </table>
             
            <table class="custom-table" style="margin-bottom: 30px;max-width:400px;">
                <thead style="background-color: #F5F5F5">
                    <tr>
                        <th>S.no</th>
                        <th>Employee Id</th>
                        <th>Name</th>
                        <th>Position</th> 
                        <th>Company</th>  
                        <th>License</th>
                        <th>Salary</th>
                        <th>Travel Allowance</th>
                        <th>House Allowance</th>
                        <th>Position Allowance</th>
                        <th>Phone Allowance</th>
                        <th>Other Allowance</th>
                        <th>Deduction</th>
                        <th>Total Earning</th>
                        <th>Cash or Bank</th>
                    </tr>
                </thead>
                <tbody>
                            
                <?php if(isset($emp_salary_data) && count($emp_salary_data) > 0): ?>
                    <?php $i = 1; ?>
                    <?php $__currentLoopData = $emp_salary_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                    <p><?php echo e($val->position); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->company_name); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->license); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->salary); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->travel_allowence ?? 0); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->house_allowence ?? 0); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->position_allowence ?? 0); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->phone_allowence ?? 0); ?></p>
                                </td>

                                <td>
                                    <p><?php echo e($val->other_allowence ?? 0); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->deduction); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->total_earning); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($val->type); ?></p>
                                </td>
                            </tr>
                            <?php $i++; ?>    
                         <?php endif; ?>  
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
               
                        </tbody>
            </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  

        <table class="table table-bordered custom-table">
            <tbody>
                <tr align="center">
                    <?php $currentMonth = date('F', mktime(0, 0, 0, $month, 10)); ?>
                    <td colspan="6"><?php echo e($currentMonth); ?> <?php echo e($year); ?><td>
                </tr>
                <tr>
                    <td></td>
                    <?php if(isset($emp_company_data) && count($emp_company_data) > 0): ?>
                        <?php $__currentLoopData = $emp_company_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ckey => $cval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td colspan="2"><?php echo e($cval->company_name); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </tr>

                <tr>
                    <td>Branch</td>
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
                                    <td><?php echo e(calcualte_total_earning_by_month_company($month,$year,$cval->company_id,$bval->id,'cash')); ?></td>
                                    <td><?php echo e(calcualte_total_earning_by_month_company($month,$year,$cval->company_id,$bval->id,'bank')); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <td><?php echo e(calcualte_total_earning_by_month_company($month,$year,$cval->company_id,$bval->id,'','total')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
    </div>
</body>
</html>
<?php //exit; ?><?php /**PATH C:\wamp64_new\www\hrm\resources\views/payroll/employee_salary_pdf.blade.php ENDPATH**/ ?>