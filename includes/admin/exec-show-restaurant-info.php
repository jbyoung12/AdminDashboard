<?php
require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();
$id = $_GET['id'];
#$num = $_GET['num'];

$restaurantRow = DB::queryFirstRow("SELECT * FROM category_items WHERE id='%d'", $id);


$hoursText = DB::queryFirstField("SELECT open_hours FROM item_hours WHERE restaurant_id=%d", $id);
$hourDict = array();

if ($hoursText!=''){
$eachDayArray  = explode(", ", $hoursText);
foreach ($eachDayArray as $day){
  $split = explode(" ", $day);
  $dayName = $split[0];
  $split2 = explode("-", $split[1]);
  $open = $split2[0];
  $close = $split2[1];
  $openTime = substr($open, 0, strlen($open)-2);
  $openAmpm = (strpos($open, "a")>-1 ? 'am' : 'pm');
  $closeTime = substr($close, 0, strlen($close)-2);
  $closeAmpm = (strpos($close, "a")>-1 ? 'am' : 'pm');
  $hourDict[$dayName] = array($openTime, $openAmpm, $closeTime, $closeAmpm);
}
//hourDict holds dictionary, key is day and value is array of above
}
else{
  $timeSelectorCode = displayTimeSelectorDefault();
}

$htmlCode = '<div class="row"><div class="col-sm-3"><label>Name of Restaurant: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="name" value="'.$restaurantRow['name'].'" required></div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Type of cuisine: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="type" value="'.$restaurantRow['type'].'" required> (e.g. Sushi, American, Salads, etc.)</div></div><br>';

//////hours
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Hours on?</label></div>
<div class="col-sm-9 col-md-6"><select class="form-control" name="hoursOn" required>
  <option '.(($hoursText!='') ? 'selected' : '').' value="1">Yes</option>
  <option '.(($hoursText=='') ? 'selected' : '').' value="0">No</option>
</select> (Is this restaurant on its normal schedule? If no, the restaurant will have no hours.)</div></div><br>';

$htmlCode .= '<div class="row"><div class="col-sm-3">Open Hours of the Restaurant:</div><div class="col-sm-9 col-md-6">
<table id="times-table">
<tr>
  <th> </th>
  <th>Opening Time</th>
  <th>Closing Time</th>
</tr>
<tr>
  <th>Monday</th>
  '.(($hoursText!='') ? displayTimes("Monday", $hourDict) : displayTimesDefault("Monday", $timeSelectorCode)).'
</tr>
<tr>
  <th>Tuesday</th>
  '.(($hoursText!='') ? displayTimes("Tuesday", $hourDict) : displayTimesDefault("Tuesday", $timeSelectorCode)).'
</tr>
<tr>
  <th>Wednesday</th>
  '.(($hoursText!='') ? displayTimes("Wednesday", $hourDict) : displayTimesDefault("Wednesday", $timeSelectorCode)).'
</tr>
<tr>
  <th>Thursday</th>
  '.(($hoursText!='') ? displayTimes("Thursday", $hourDict) : displayTimesDefault("Thursday", $timeSelectorCode)).'
</tr>
<tr>
  <th>Friday</th>
  '.(($hoursText!='') ? displayTimes("Friday", $hourDict) : displayTimesDefault("Friday", $timeSelectorCode)).'
</tr>
<tr>
  <th>Saturday</th>
  '.(($hoursText!='') ? displayTimes("Saturday", $hourDict) : displayTimesDefault("Saturday", $timeSelectorCode)).'
</tr>
<tr>
  <th>Sunday</th>
  '.(($hoursText!='') ? displayTimes("Sunday", $hourDict) : displayTimesDefault("Sunday", $timeSelectorCode)).'
</tr>
</table></div></div></div></br>';


$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Restaurant\'s Phone Number: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="tel" name="phone" value="'.$restaurantRow['phoneNumber'].'" required></div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Email of Printer: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="email" name="email" value="'.$restaurantRow['email'].'" required></div></div><br>';

$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Payment Options: </label></div><div class="col-sm-9 col-md-6">
<select name="paymentOptions[]" class="editMultiSelect" multiple="multiple" required>
<option value="duke" '.(strpos($restaurantRow['payment_options'], 'duke') !== false ? 'selected' : '').'>Duke Card</option>
<option value="credit-card" '.(strpos($restaurantRow['payment_options'], 'credit-card') !== false ? 'selected' : '').'>Credit Card</option>
</select> (which payment platforms the restaurant accepts)</div></div><br>';

$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Delivery Fee: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="number" name="deliveryFee" value="'.$restaurantRow['delivery_fee'].'" min="0" step=".01" required> (how much to charge per delivery)</div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Minimum Price: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="number" name="minimumPrice" value="'.$restaurantRow['minimum_price'].'" min="0" step=".01" required> (minimum total order price for delivery to be accepted)</div></div><br>';
$htmlCode .= '<input type="hidden" name="MAX_FILE_SIZE" value="512000" />
<div class="row"><div class="col-sm-3"><label>Image (on the main page, upload if you want to change it): </label></div><div class="col-sm-9 col-md-6"><input class="form-control" name="image" type="file"/></div></div><br>';

echo $htmlCode;

//$openorclose is 0 if open time, and 1 if close time
function displayTimeSelector($day, $openorclose, $hourDict){
  $htmlCode = '';
  for($i = 0; $i < 24; $i++){
    if ($i == 0 or $i==1)
      $time = sprintf("%d:%2$02d", 12, $i%2*30);
    else
      $time = sprintf("%d:%2$02d", $i/2, $i%2*30);
    if ($openorclose == 0){
    $htmlCode .= '<option '.(strpos($time, $hourDict[$day][0])>-1 ? 'selected' : '').' value="'.$time.'">'.$time.'</option>';
  }
  else{
    $htmlCode .= '<option '.(strpos($time, $hourDict[$day][2])>-1 ? 'selected' : '').' value="'.$time.'">'.$time.'</option>';
  }
}
return $htmlCode;
}

function displayTimes($day, $hourDict){
    $htmlCode = '<td>';
    $htmlCode .= '<select name="'.$day.'open'.'" required>';
    $htmlCode .= displayTimeSelector($day, 0, $hourDict);
    $htmlCode .= '</select>';
    $htmlCode .= '<select name="'.$day.'ampmopen'.'" required>';
    $htmlCode .= '<option '.($hourDict[$day][1]=='am' ? 'selected' : '').' value="am">am</option>';
    $htmlCode .= '<option '.($hourDict[$day][1]=='pm' ? 'selected' : '').' value="pm">pm</option>';
    $htmlCode .= '</select></td>';


    $htmlCode .= '<td>';
    $htmlCode .= '<select name="'.$day.'close'.'" required>';
    $htmlCode .= displayTimeSelector($day, 1, $hourDict);
    $htmlCode .= '</select>';
    $htmlCode .= '<select name="'.$day.'ampmclose'.'" required>';
    $htmlCode .= '<option '.($hourDict[$day][3]=='am' ? 'selected' : '').' value="am">am</option>';
    $htmlCode .= '<option '.($hourDict[$day][3]=='pm' ? 'selected' : '').' value="pm">pm</option>';
    $htmlCode .= '</select></td>';
    return $htmlCode;
}

function displayTimeSelectorDefault(){
  $htmlCode = '';
  for($i = 0; $i < 24; $i++){
    if ($i == 0 or $i==1)
      $time = sprintf("%d:%2$02d", 12, $i%2*30);
    else
      $time = sprintf("%d:%2$02d", $i/2, $i%2*30);
    $htmlCode .= '<option value="'.$time.'">'.$time.'</option>';
}
return $htmlCode;

}

function displayTimesDefault($day, $timeSelectorCode){
    $htmlCode = '<td>';
    $htmlCode .= '<select name="'.$day.'open'.'" required>';
    $htmlCode .= $timeSelectorCode;
    $htmlCode .= '</select>';
    $htmlCode .= '<select name="'.$day.'ampmopen'.'" required>';
    $htmlCode .= '<option value="am">am</option>';
    $htmlCode .= '<option value="pm">pm</option>';
    $htmlCode .= '</select></td>';


    $htmlCode .= '<td>';
    $htmlCode .= '<select name="'.$day.'close'.'" required>';
    $htmlCode .= $timeSelectorCode;
    $htmlCode .= '</select>';
    $htmlCode .= '<select name="'.$day.'ampmclose'.'" required>';
    $htmlCode .= '<option value="am">am</option>';
    $htmlCode .= '<option value="pm">pm</option>';
    $htmlCode .= '</select></td>';
    return $htmlCode;
}
