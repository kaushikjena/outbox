<?php
echo $date = date("2015-12-31");
echo date("W",strtotime($date));
//echo date('d.m.Y',strtotime('last monday')); 
//echo date('d.m.Y',strtotime('first monday'));
//echo $date = strtotime('now');

/*function week_number( $date = 'today' ) 
{ 
    return ceil( date( 'j', strtotime( $date ) ) / 7 ); 
} 
echo week_number(date("Y-m-d"));*/
function week_dates($date = null, $format = null, $start = 'monday') {
     //date_default_timezone_set(date_default_timezone_get());
  // is date given? if not, use current time...
  if(is_null($date)) $date = 'now';

  // get the timestamp of the day that started $date's week...
  $weekstart = strtotime('last '.$start, strtotime($date));

  // add 86400 to the timestamp for each day that follows it...
  for($i = 0; $i < 7; $i++) {
    $day = $weekstart + (86400 * $i);
    if(is_null($format)) $dates[$i] = $day;
    else $dates[$i] = date($format, $day);
  }

  return $dates;
}

$weeks= week_dates("2014-05-04","Y-m-d","monday");
print "<pre>";
//print_r($weeks);

function week_of_month($date) {
    $date_parts = explode('-', $date);
    $date_parts[2] = '01';
    $first_of_month = implode('-', $date_parts);
    $day_of_first = date('N', strtotime($first_of_month));
    $day_of_month = date('j', strtotime($date));
    return floor(($day_of_first + $day_of_month - 1) / 7) + 1;
}

//echo week_of_month("2014-12-31");

function getStartAndEndDate($week, $year) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  $ret['week_start'] = $dto->format('Y-m-d');
  $dto->modify('+6 days');
  $ret['week_end'] = $dto->format('Y-m-d');
  return $ret;
}

//$week_array = getStartAndEndDate(21,2014);
//print_r($week_array);

$first_day_this_month = date('Y-m-01');
$last_day_this_month  = date('Y-m-t');
$current_year = date("Y");

$first_week_no = date("W",strtotime(date($first_day_this_month)));//first week number of current month
$last_week_no = date("W",strtotime(date($last_day_this_month)));//last week number of current month
 
for($i=$first_week_no; $i<=$last_week_no; $i++){
	$week_array[] = getStartAndEndDate($i,$current_year);
	
}
//print_r($week_array);

//$start_date = '2013-02-01';
//$end_date = '2013-02-28';
/*
getWeekDates($first_day_this_month, $last_day_this_month);

function getWeekDates($date, $enddate) {
$week = date('W', strtotime($date));
$year = date('Y', strtotime($date));
$from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
$to = date("Y-m-d", strtotime("{$year}-W{$week}-7"));   //Returns the date of sunday in week
$Edate = strtotime($enddate);
$Sdate = strtotime($to);
if ($Edate <= $Sdate) {
    echo "<br>Start Date-->" . $from . "End Date -->" . $enddate; //Output : Start Date-->2012-09-03 End Date-->2012-09-09

} else {
    echo "<br>Start Date-->" . $from . "End Date -->" . $to; //Output : Start Date-->2012-09-03 End Date-->2012-09-09
    $to = date("Y-m-d", strtotime("$to +1days")); //Returns the date of monday in week
    getWeekDates($to, $enddate);
}
}*/
//getting the first and last date of any given month and year.
$month="02";
$year = "2014";
$first = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
$last = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));