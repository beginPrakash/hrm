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
            <h3 class="page-title">Passport Reports</h3>
            <table class="custom-table" style="margin-bottom: 30px;max-width:400px;">
                <thead style="background-color: #F5F5F5">
                    <tr>
                        <th>Sr.No</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Civil Id</th>
                        <th>Designation</th>
                        <th>Date Of Joining</th>
                        <th>Expired</th>
                        <th>Is Passport</th>
                        <th>Hiring Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                            
                    <?php if(isset($data_list) && count($data_list) > 0): ?>
                        <?php $i = 1; ?>
                        <?php $__currentLoopData = $data_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $passport_expiry =  $data->passport_expiry ?? '';
                            if(!empty($passport_expiry)):
                                $exp_str = strtotime($passport_expiry);
                                $cur_str = strtotime(date('Y-m-d'));
                                if($exp_str < $cur_str):
                                    $status = 'Expired';
                                else:
                                    $status = 'Active';
                                endif;
                            else:
                                $status = '';
                            endif;
                            
                            ?>
                            <tr>
                                <td>
                                    <h3 class="mb-0"><?php echo e($i); ?></h3>
                                </td>
                                <td>
                                    <p><?php echo e($data->emp_generated_id); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($data->first_name); ?> <?php echo e($data->last_name); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e((isset($data->employee_details) && !empty($data->employee_details)) ? $data->employee_details->c_id : ''); ?></p> 
                                </td>
                                <td>
                                    <p><?php echo e((isset($data->employee_designation) && !empty($data->employee_designation)) ? $data->employee_designation->name : ''); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e(date('d, M Y', strtotime($data->joining_date))); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e((isset($data) && !empty($data->passport_expiry)) ? date('d, M Y', strtotime($data->passport_expiry)) : ''); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e(($data->is_passport==1) ? 'Yes' : 'No'); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e(ucfirst($data->hiring_type) ?? ''); ?></p>
                                </td>
                                <td>
                                    <p><?php echo e($status); ?></p>
                                </td>
                            </tr>
                            <?php $i++; ?>    
                       
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </tbody>
            </table>
       
        


    
    </div>
</body>
</html>
<?php //exit; ?><?php /**PATH C:\wamp64_new\www\hrm\resources\views/reports/passport_report_pdf.blade.php ENDPATH**/ ?>