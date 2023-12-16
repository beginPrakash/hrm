<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<head>


    <style type="text/css">
        body {
            font-family: "CircularStd", sans-serif;
            font-size: 15px;
        }

        * {
            box-sizing: border-box;
        }

        .color-white {
            color: white;
        }

        .color-2b5da7 {
            color: #2b5da7;
        }

        .color-0791bb {
            color: #0791bb;
        }

        .color-5b9bd5 {
            color: #5b9bd5;
        }

        .color-bb0707 {
            color: #bb0707;
        }

        .bg-color-2b5da7 {
            background-color: #2b5da7;
        }

        .bg-color-0799c3 {
            background-color: #0799c3;
        }

        .bg-color-5b9bd5 {
            background-color: #5b9bd5;
        }

        img {
            max-width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }


        /************************************ */
        .logo-wrap {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr th,
        table tr td {
            padding: 5px 10px;
            text-align: left;
        }

        .custom-table th {
            padding: 5px 10px;
            font-size: 16px;
        }

        .custom-table tr {
            border-bottom: 1px solid #dee2e6;
        }

        .custom-table h3 {
            font-size: 14px;
            line-height: 16px;
            margin-bottom: 0;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            padding: 3px 10px;
            border: 1px solid #dee2e6;
        }

        .card {
            position: relative;
            margin-top: 25px;
            margin-bottom: 25px;
            padding: 20px;
            border-radius: 25px;
            border: 2px solid #0C0F1E;
        }
    </style>
</head>

<body>


    <table style="width:600px;margin:auto;">
        <thead>
            <tr>
                <th colspan="2" style="text-align:center;"><?php echo e((isset($employee->employee_company_details) &&
                    !empty($employee->employee_company_details)) ?
                    $employee->employee_company_details->company_name : ''); ?> </th>
            </tr>
            <tr>
                <th colspan="2" style="text-align:center;">Vacation settlement (<?php echo e(date('d M y',
                    strtotime($leave_data->updated_at))); ?>) </th>
            </tr>
            <tr>
                <th colspan="2" style="text-align:center;"><?php echo e($employee->full_name ?? ''); ?> -
                    <?php echo e($employee->emp_generated_id ?? ''); ?> -
                    <?php echo e($employee->employee_details ? $employee->employee_details->c_id : '--'); ?></th>
            </tr>
        </thead>
    </table>
    <table style="width:700px;margin:20px auto 0;border: 1px solid #dee2e6;
  border-collapse: collapse;">
        <tbody>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">Salary of employee :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e(number_format($leave_data->basic_salary,2) ?? 0); ?> KWD</td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">Number of leave in bucket :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e($leave_data->claimed_annual_days +
                    $leave_data->claimed_annual_days_rem); ?></td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">Number of leave approved :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e($leave_data->claimed_annual_days ?? ''); ?></td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">Date of leaves approved :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e(date('d M y', strtotime($leave_approve_date))); ?></td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">No of leave remaining :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e($leave_data->claimed_annual_days_rem ?? ''); ?></td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">Number of leaved paying :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e(number_format(_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_annual_days
                    ?? 0),2)); ?> KWD</td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">Balance amount remains :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e(number_format(_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_annual_days_rem
                    ?? 0),2)); ?> KWD</td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">No of public holidays :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e($leave_data->claimed_public_days ?? 0 +
                    $leave_data->claimed_public_days_rem ?? 0); ?></td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">No of public holidays paying :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e(number_format(_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_public_days
                    ?? 0),2)); ?> KWD</td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">No of public holidays Remains :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e($leave_data->claimed_public_days_rem ?? 0); ?></td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">Payable amount :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e(number_format((_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_annual_days
                    ?? 0)) +
                    (_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_public_days ??
                    0)),2)); ?> KWD</td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">Number of leaved paying :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e($leave_data->claimed_annual_days +
                    $leave_data->claimed_public_days); ?></td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">No of public holidays Remains : </td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e($employee->public_holidays_balance ?? 0); ?></td>
            </tr>
            <tr>
                <td style="width:40%;background:#f5f5f5;border-bottom: 1px solid #dee2e6;padding:8px 10px;">Total Payable amount :</td>
                <td style="border-bottom: 1px solid #dee2e6;padding:8px 10px;"><?php echo e(number_format((_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_annual_days
                    ?? 0)) +
                    (_calculate_salary_by_days($leave_data->basic_salary,$leave_data->claimed_public_days ??
                    0)),2)); ?> KWD</td>
            </tr>
        </tbody>
    </table>
    <table style="width:700px;margin:20px auto 0;">
        <tbody>
            <tr>
                <td style="width:33.33%;"><b>HR Sign.</b></td>
                <td style="width:33.33%;text-align:center;"><b>Finanace Sign.</b></td>
                <td style="width:33.33%;text-align:right;"><b>GM Sign.</b></td>
            </tr>
        </tbody>
    </table>

</body>

</html>
<?php //exit; ?><?php /**PATH C:\wamp64\www\hrm\resources\views/edbr/vacation_history_pdf.blade.php ENDPATH**/ ?>