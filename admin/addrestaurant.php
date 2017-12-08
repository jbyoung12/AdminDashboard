
<?php

require_once '../includes/all.php';
$timeSelectorCode = displayTimeSelector();

$cookies = new Cookies();
$user    = $cookies->user_from_cookie();
if ($user === 0) {
	header("Location: /index.php?m=9");
  exit;
}
$permission = $user->data["permission"];
if ($permission < 2) {
	header("Location: /index.php?m=9");
  exit;
}


function displayTimeSelector(){
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

function displayTimes($day, $timeSelectorCode){
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
?>

<head>
  <title> </title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width initial-scale=1">
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <!--[if lte IE 8]><script src="../css/ie/html5shiv.js"></script><![endif]-->
  <script src="../js/jquery.min.js"></script>
  <script src="../js/jquery.scrolly.min.js"></script>
	<script src="../js/multiple-select.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/admin.css" />
	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">

 <!--[if lte IE 9]><link rel="stylesheet" href="../css/ie/v9.css" /><![endif]-->
 <!--[if lte IE 8]><link rel="stylesheet" href="../css/ie/v8.css" /><![endif]-->
</head>
<body>
	<div id="orderContainer">

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-md-9">
	<h2>Add Restaurant</h2>
	</div>
</div>
	<form action="../includes/admin/exec-add-category-item.php" enctype="multipart/form-data" method="post">

	<div class="row"><div class="col-sm-3"><label>Name of Restaurant:</label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="name" required></div></div><br>
	<div class="row"><div class="col-sm-3"><label>Type of cuisine:</label></div><div class="col-sm-9 col-md-6"> <input class="form-control" type="text" name="type" required> (e.g. Sushi, American, Salads, etc.)</div></div><br>
	<div class="row"><div class="col-sm-3"><label>Hours on?</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" name="hoursOn" required>
	  <option value="1">Yes</option>
	  <option value="0">No</option>
	</select> (Is this restaurant on its normal schedule? If no, the restaurant will have no hours.)</div></div><br>
<div class="row"><div class="col-sm-3">
	Open Hours of the Restaurant:</div>
	<div class="col-sm-9 col-md-6">
			<table id="times-table">
  <tr>
		<th> </th>
		<th>Opening Time</th>
		<th>Closing Time</th>
  </tr>
	<tr>
		<th>Monday</th>
		<?php echo displayTimes("Monday", $timeSelectorCode) ?>
	</tr>
	<tr>
		<th>Tuesday</th>
		<?php echo displayTimes("Tuesday", $timeSelectorCode) ?>
	</tr>
	<tr>
		<th>Wednesday</th>
		<?php echo displayTimes("Wednesday", $timeSelectorCode) ?>
	</tr>
	<tr>
		<th>Thursday</th>
		<?php echo displayTimes("Thursday", $timeSelectorCode) ?>
	</tr>
	<tr>
		<th>Friday</th>
		<?php echo displayTimes("Friday", $timeSelectorCode) ?>
	</tr>
	<tr>
		<th>Saturday</th>
		<?php echo displayTimes("Saturday", $timeSelectorCode) ?>
	</tr>
	<tr>
    <th>Sunday</th>
		<?php echo displayTimes("Sunday", $timeSelectorCode) ?>
  </tr>
</table><br></div></div>

	<div class="row"><div class="col-sm-3"><label>Restaurant's Phone Number:</label></div><div class="col-sm-9 col-md-6"> <input class="form-control" type="tel" name="phone" required></div></div><br>
	<div class="row"><div class="col-sm-3"><label>Email of Printer:</label></div><div class="col-sm-9 col-md-6"> <input class="form-control" type="email" name="email" required></div></div><br>
	<div class="row"><div class="col-sm-3"><label>Payment Options:</label></div><div class="col-sm-9 col-md-6">
	<select name="paymentOptions[]" class="multiSelect" multiple="multiple" required>
		<option value="duke">Duke Card</option>
	  <option value="credit-card">Credit Card</option>
	</select> (which payment platforms the restaurant accepts)</div></div><br>
	<div class="row"><div class="col-sm-3"><label>Delivery Fee:</label></div><div class="col-sm-9 col-md-6"> <input class="form-control" type="number" name="deliveryFee" min="0" step=".01" required> (how much to charge per delivery)</div></div><br>
	<div class="row"><div class="col-sm-3"><label>Minimum Price:</label></div><div class="col-sm-9 col-md-6"> <input class="form-control" type="number" name="minimumPrice" min="0" step=".01" required> (minimum total order price for delivery to be accepted)</div></div><br>
	<input class="form-control" type="hidden" name="MAX_FILE_SIZE" value="512000" />
	<div class="row"><div class="col-sm-3"><label>Image (on the main page):</label></div><div class="col-sm-9 col-md-6"> <input class="form-control" name="image" type="file" required/></div></div><br>
	<div class="row"><div class="col-sm-12 offset-md-3 col-md-6"><input class="form-control btn btn-primary" type="submit"></div></div>
	</form>
</div>
</div>

</body>

<script>
$(".multiSelect").multipleSelect({
	width: $(".multiSelect").parent().width()
});
</script>
