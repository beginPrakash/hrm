<?php
function calculateLeave($joiningDate, $currentDate) {
   
    $joiningDateTime = new DateTime($joiningDate);
    $currentDateTime = new DateTime($currentDate);

    // Initialize variables
    $totalLeave = '0';

    // Loop through each month
    while ($joiningDateTime <= $currentDateTime) {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $joiningDateTime->format('m'), $joiningDateTime->format('Y'));
        $leaveThisMonth = min('2.5', bcdiv(bcmul($currentDateTime->diff($joiningDateTime)->format('%a'), '2.5'), $daysInMonth, 10));
        $totalLeave = bcadd($totalLeave, $leaveThisMonth, 10);

        // Move to the next month
        $joiningDateTime->modify('first day of next month');
    }

    return $totalLeave;
}

// Example usage:


$totalLeave = calculateLeave($_GET['date'], $_GET['cdate']);

echo $totalLeave;
?>