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

$alertMessageQuery = DB::query("SELECT VALUE, DISPLAY, EXTRA from settings where name='alert_message_web'");
$alertMessage = $alertMessageQuery[0]['VALUE'];
$alertMessageQueryApp = DB::query("SELECT VALUE, DISPLAY from settings where name='alert_message'");
$alertMessageApp = $alertMessageQueryApp[0]['VALUE'];
$color = $alertMessageQuery[0]['DISPLAY'];
$link = $alertMessageQuery[0]['EXTRA'];

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
  <h2>Edit Announcement Bar</h2>
</div>
</div>
  <form action="../includes/admin/exec-edit-alert-message.php" method="get">
		<div class="row">
			<div class="col-sm-3">
				<label>
  Announcement Bar (Web): </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" id="large-text" name="alertMessage" value="<?php echo $alertMessage; ?>"></div>
</div><br>
	<div class="row">
		<div class="col-sm-3">
			<label>
Color on Web (in hex): </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="color" value="<?php echo $color; ?>">Leave blank for default color (#FF5959).</div></div><br>
<div class="row">
	<div class="col-sm-3">
		<label>
Link: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="link" value="<?php echo $link; ?>">Leave blank for no link.</div></div><br>
<div class="row">
	<div class="col-sm-3">
		<label>
Announcement Bar (App): </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" id="large-text" name="alertMessageApp" value="<?php echo $alertMessageApp; ?>"></div>
</div><br>
<div class="row"><div class="col-sm-12 offset-md-3 col-md-6"><input class="form-control btn btn-primary" type="submit"></div></div>
  </form>
</div>
