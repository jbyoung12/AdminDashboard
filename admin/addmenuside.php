<?php

require_once '../includes/all.php';

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


$services = DB::query("SELECT ID, NAME FROM category_items");

function displayServices($data) {
		$htmlCode = '';
		foreach ($data as $row) {
			$htmlCode .= '<option value="'.$row['ID'].'">'.$row['NAME'].'</option>';
		}
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

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-md-9">
	<h2>Add Menu Side</h2>
</div>
</div>
	<form action="../includes/admin/exec-add-menu-side.php" method="post">

<div class="row"><div class="col-sm-3"><label>
	Restaurant:
	</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" id="restaurantId" name="serviceId" required>
	<?php
	echo displayServices($services);
	?>
</select> (restaurant for which this menu side will be added)</div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Name of Menu Side: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="name" required></div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Price: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="number" name="price" min="0" step=".01" required> (0 if free)
</div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Required?
	</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" name="required" required>
		<option value="0">No</option>
		<option value="1">Yes</option>
	</select> (Is this menu item a required side or an extra?)</div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Grouping (Not Required): </label></div><div class="col-sm-9 col-md-6"><input type="text" name="grouping" class="form-control notRequiredFormatter"> (Should this menu side appear in a grouping (e.g. rice, protein, sauce, etc.)? If so, list it here)
</div></div><br>
<div class="row"><div class="col-sm-12 offset-md-3 col-md-6"><input class="form-control btn btn-primary" type="submit"></div></div>
	</form>
</div>
