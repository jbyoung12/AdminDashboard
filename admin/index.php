<?php

require_once '../includes/all.php';

$cookies = new Cookies();
$user    = $cookies->user_from_cookie();
if ($user === 0) {
	header("Location: ../index.php?m=9");
  exit;
}
$permission = $user->data["permission"];
if ($permission < 2) {
	header("Location: ../index.php?m=9");
  exit;
}


?>

<!DOCTYPE HTML>
<html>
<head>
  <title> </title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="initial-scale=1">
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <!--[if lte IE 8]><script src="../css/ie/html5shiv.js"></script><![endif]-->
  <script src="../js/jquery.min.js"></script>
  <script src="../js/jquery.scrolly.min.js"></script>
  <script src="../js/skel.min.js"></script>
  <script src="../js/skel-layers.min.js"></script>
  <script src="../js/featherlight.js"></script>
  <script src="../js/init.js"></script>
  <script src="../js/cam.js"></script>
	<script src="../js/multiple-select.js"></script>

  <noscript>
   <link rel="stylesheet" href="../css/skel.css" />
   <link rel="stylesheet" href="../css/admin.css" />
<link rel="stylesheet" href="../css/index.css" />
   <link rel="stylesheet" href="../css/style-xlarge.css" />
 </noscript>
 <link rel="stylesheet" href="../css/flightbox.css">
 <!--[if lte IE 9]><link rel="stylesheet" href="../css/ie/v9.css" /><![endif]-->
 <!--[if lte IE 8]><link rel="stylesheet" href="../css/ie/v8.css" /><![endif]-->
</head>
<body>

  <!-- Header -->
  <header id="header-admin">
    <h1><a href="index.php"><?php echo getSetting("sitename"); ?></a></h1>
    <nav id="nav">
     <ul>
      <li><a class="admin-name" href="#">Welcome, <?php echo displayCurrentUserName();?>!</a></li>
		</ul>
</nav>
</header>

<!-- Main -->
<div id="orderContainer">

 <div id="admin-switch" class="section group">
  <div id="switch-left" class="col span_1_of_2">
    <a href="index.php"><span id="switch-underline">admin view</span></a>
  </div>
  <div id="switch-right" class="col span_1_of_2">
    <a href="../index.php"><span id="switch-underline">customer view</span></a>
  </div>
</div>

<div class="container admin-adds">
	<h3>Admin Dashboard</h3><br>
	<div class="row">
		<div class="col-sm-4">
				<ul> Recent Orders</ul>
			<li> <a href="#orderCount">View Order Count</a> </li>
		 <li> <a href="#recentOrders">View Recent Orders</a> </li>
	 </div>
 		<div class="col-sm-4">
		 <ul>Add</ul>
     <li> <a href="addrestaurant.php">Add Restaurant</a> </li>
     <li> <a href="addmenucategory.php">Add Menu Category</a> </li>
     <li> <a href="addmenuitem.php">Add Menu Item</a> </li>
     <li> <a href="addmenuside.php">Add Menu Side</a> </li>
	 </div>
			 <div class="col-sm-4">
		 <ul>Edit</ul>
		 <li> <a href="editalertmessage.php">Edit Announcement Bar</a></li>
		 <li> <a href="editrestaurant.php">Edit Restaurant Info</a> </li>
		 <li> <a href="editmenucategory.php">Edit Menu Category Info</a> </li>
		 <li> <a href="editmenuitem.php">Edit Menu Item Info</a> </li>
		 <li> <a href="editmenuside.php">Edit Menu Side Info</a> </li>
	 </div>
			 <div class="col-sm-4">
		 <ul>Delete</ul>
		 <li> <a href="deleterestaurant.php">Delete Restaurant</a> </li>
		 <li> <a href="deletemenucategory.php">Delete Menu Categories</a> </li>
		 <li> <a href="deletemenuitems.php">Delete Menu Items</a> </li>
		 <li> <a href="deletemenusides.php">Delete Menu Sides</a> </li>
	 </div>
			 <div class="col-sm-4">
			 <ul>Admin</ul>
		 <li> <a href="makeadmin.php">Make Admin</a> </li>
	 </div>
 </div>

	 <div class="tab-content">

		 <div id="orderCount" class="add-form tab-pane fade in active" style="width: 100%">
			<h2>Order Count</h2>
			<?php include_once "../includes/admin/exec-get-order-count.php";?>
		</div>

		 <div id="recentOrders" class="add-form tab-pane fade in active" style="width: 100%;height: 400px;overflow: scroll;">
		 	<h2>Recent Orders</h2>
			<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
			<tr>
					<td align="left" width="10%"><em>Order ID:</em></td>
					<td align="left" width="20%"><span>Order Time</span>:</td>
					<td align="left" width="10%"><span>Restaurant</span>:</td>
					<td align="left" width="50%"><span>Items</span>:</td>
					<td align="left" width="10%"><em>Total:</em></td>
			</tr>
			<?php include_once "../includes/admin/exec-get-recent-orders.php";?>
	</table>
</div>

</div>
</div>
</div>
</body>

<footer>
  <div id="footerLinks">
    <a href="http://www.campusenterprises.org" onClick ="ga('send', 'event', { eventCategory: 'Home', eventAction: 'Click', eventLabel: 'About'});">about</a>
    <a href="http://www.campusenterprises.org/bringit-bug-report" onClick ="ga('send', 'event', { eventCategory: 'Home', eventAction: 'Click', eventLabel: 'Support'});">support</a>
    <a href="http://www.campusenterprises.org/restaurant-account" onClick ="ga('send', 'event', { eventCategory: 'Home', eventAction: 'Click', eventLabel: 'Restaurant Account'});">restaurant account</a>
  </div>
  <div id="footerCopyright">
    Copyright &copy; 2009 &middot; All Rights Reserved &middot; <a href="http://www.campusenterprises.org" onClick ="ga('send', 'event', { eventCategory: 'Home', eventAction: 'Click', eventLabel: 'CE Email'});">Campus Enterprises</a>
  </div>
</footer>

  </html>
