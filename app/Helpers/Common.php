<?php
function getDefTime()
{
    date_default_timezone_set(env('DEFAULT_TIME_ZONE'));
    return date('Y-m-d h:i:s a', strtotime("now +3 GMT"));
}

function getTimeDiff($start, $end)
{
    if($start === '0' || $end === '0')
    {
        return '0:0';
    }

    if (str_contains($start, 'pm') && str_contains($end, 'am')) { 
        $start = str_replace('pm', 'am', $start);
        $end = str_replace('am', 'pm', $end);
    }

    $datetime1 = new DateTime($start);
    $datetime2 = new DateTime($end);

    $interval = $datetime1->diff($datetime2);
    $timeDiff = $interval->format('%H:%I:%S');
    return $timeDiff;
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function checkDateTimeInBetween($checkdatetime, $mindatetime, $maxdatetime)
{
    $checkdatetime = strtotime($checkdatetime);
    $mindatetime = strtotime($mindatetime);
    $maxdatetime = strtotime($maxdatetime);
// echo $checkdatetime.'-';
    if($checkdatetime >= $mindatetime && $checkdatetime <= $maxdatetime) {
       return 1;
       // echo "is between";
    } else {
        return 2;
        // echo "NO GO!";  
    }    
}

function noOfFridays($months, $years)
{
    // $months = 12;  
    // $years=2016;                                      
    $monthName = date("F", mktime(0, 0, 0, $months));
    $fromdt=date('Y-m-01 ',strtotime("First Day Of  $monthName $years")) ;
    $todt=date('Y-m-d ',strtotime("Last Day of $monthName $years"));

    $num_fridays='';                
    for ($i = 0; $i < ((strtotime($todt) - strtotime($fromdt)) / 86400); $i++)
    {
        if(date('l',strtotime($fromdt) + ($i * 86400)) == 'Friday')
        {
            $num_fridays++;
        }    
    }
    return $num_fridays;
}


function addTimeDiff($times) {
    $minutes = 0; //declare minutes either it gives Notice: Undefined variable
    // loop throught all the times
    foreach ($times as $time) {
        if(isset($time))
        {
            list($hour, $minute) = explode(':', $time);
            if(isset($minutes))
            {
                $minutes += $hour * 60;
                $minutes += $minute;
            }
            else
            {
                $minutes = 0;
            }
        }
         else
            {
                $minutes = 0;$hours = 0;
            }
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    // returns the time already formatted
    return sprintf('%02d:%02d', $hours, $minutes);
}

function numberToWords($number) 
{
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ? "." . $words[$point / 10] . " " . 
        $words[$point = $point % 10] : '';
    return $result . " Only";
}

function getDateDiff($date1, $date2, $days=0)
{
    $date1 = new DateTime(date('Y-m-d', strtotime(str_replace('/','-',$date1))));
    $date2 = new DateTime($date2);

    $interval = $date1->diff($date2);

    if($days == 1)
    {
        return $interval->format("%a");
    }
    else
    {
        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;
        return $interval->format('%y.%m.%d');
    }
}

function changeDate($ddate)
{
    date_default_timezone_set(env('DEFAULT_TIME_ZONE'));
    $result = date('Y-m-d', strtotime(str_replace('-','/', $ddate)));
    if(strtotime($result) !== false && $result !== '1970-01-01')
    {
        return $result;
    }
    return NULL;
}

function changeDateSlash($ddate)
{
    date_default_timezone_set(env('DEFAULT_TIME_ZONE'));
    $result = date('Y-m-d', strtotime(str_replace('/','-', $ddate)));
    if(strtotime($result) !== false && $result !== '1970-01-01')
    {
        return $result;
    }
    return NULL;
}

function dateDisplayFormat($ddate)
{
    // date_default_timezone_set(env('DEFAULT_TIME_ZONE'));
    if(strtotime($ddate) > 0)
    {
        return date('d/m/Y', strtotime(str_replace('-','/', $ddate)));
    }
    return '--';
}

function getShiftName($id)
{
    $shifts = array(
        1   =>  'OFF',
        2   =>  'PH',
        3   =>  'FS',
        7   =>  'AL',
        8   =>  'SL',
        9   =>  'UL',
        10   =>  'COD');
    return $shifts[$id];
}

function getAttendanceText($shiftDetails,$encoded='')
{
    if(!empty($shiftDetails) && (in_array($shiftDetails->shift, array(1,2,3,7,8,9,10))))
    {
        $tdValue = getShiftName($shiftDetails->shift);
    }
    else
    {
        $tdValue = '<a href="javascript:void(0);" class="CreateAttPopup" data-id="'.$encoded.'"><span class="text-danger">A</span></a>';
    }

    return $tdValue;
}