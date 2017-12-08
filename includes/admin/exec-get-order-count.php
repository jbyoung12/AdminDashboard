<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

$days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
$dayCountArray = array();

$UTC = new DateTimeZone('UTC');
$usersTimezone = new DateTimeZone('America/New_York');

$currentTime = new DateTime();
$currentTime->setTimeZone($usersTimezone);
$weekday = $currentTime->format('l'); //gets current day, e.g. Monday

$time = $currentTime->format('Y-m-d');
$timemin = new DateTime($time, $usersTimezone); //gets start of current day
$timemax = new DateTime($time, $usersTimezone);

$timemin ->setTimeZone($UTC); //changes to UTC
$timemax ->setTimeZone($UTC); //changes to UTC

$timemax->modify('+1 day');
$stringtimemax = $timemax->format('Y-m-d H:i:s');
$stringtimemin = $timemin->format('Y-m-d H:i:s');


for ($i = 0; $i < 7; $i++) { #creates dict of weekday and count
  $count = DB::queryOneField('count(*)', "SELECT count(*) from orders where orders.service_id = 1 and orders.time<%s and orders.time>%s", $stringtimemax, $stringtimemin);
  $rev = DB::queryOneField('sum(price)', "SELECT sum(price) from ItemRevenue where ItemRevenue.cart_type = 1 and ItemRevenue.time < %s and ItemRevenue.time > %s", $stringtimemax, $stringtimemin);
  $rev += DB::queryOneField('sum(price)', "SELECT sum(price) from SideRevenue where SideRevenue.cart_type = 1 and SideRevenue.time < %s and SideRevenue.time > %s", $stringtimemax, $stringtimemin);

  //$dayCountArray[$days[$weekday]] = [$count, $rev];
  $dayCountArray[$weekday] = [$count, $rev];
  $timemax->modify('-1 day');
  $timemin->modify('-1 day');
  $currentTime->modify('-1 day');
  $weekday = $currentTime->format('l');
  $stringtimemax = $timemax->format('Y-m-d H:i:s');
  $stringtimemin = $timemin->format('Y-m-d H:i:s');
}
$htmlCode = '<table class="orderCountTable">
  <tr><th>

  </th>';

$countCode = '<tr><th>
Count
</th>';
$revCode = '<tr><th>
Amount
</th>';
  foreach($dayCountArray as $day => $arr){
      $htmlCode .= '<th>'.$day.'</th>';
      $countCode .= '<td>'.$arr[0].'</td>';
      $revCode .= '<td>$'.number_format(floatval($arr[1]), 2, '.', '').'</td>';
  }
$htmlCode .= '</tr>';
$countCode .= '</tr>';
$revCode .= '</tr>';
$htmlCode .= $countCode;
$htmlCode .= $revCode;

$htmlCode .= '</table>';

#html for table
echo $htmlCode;
