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

<script>

function showMenuCategories(id, elementId) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(elementId).innerHTML = this.responseText;
								$(".deleteMenuCategories").multipleSelect({
									 width: $(".deleteMenuCategories").parent().width()
							 });
            }
        };
        xmlhttp.open("GET","../includes/admin/exec-show-menu-categories-for-restaurant.php?id="+id,true);
        xmlhttp.send();
}


function showMenuItems(id, elementId) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(elementId).innerHTML = this.responseText;
								$(".deleteMenuItems").multipleSelect({
									 width: $(".deleteMenuItems").parent().width(),
									 multiple: true,
									 multipleWidth: 250
							 });
            }
        };
        xmlhttp.open("GET","../includes/admin/exec-show-menu-items-for-category.php?id="+id,true);
        xmlhttp.send();
}


function showMenuItemInfo(id) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("menuItemInfo").innerHTML = this.responseText;
								$(".menuItemEditSides").multipleSelect({
									  width: $(".menuItemEditSides").parent().width(),
									 multiple: true,
									 multipleWidth: 250
							 });
            }
        };
				restaurantId = document.getElementById("editMenuItemRestaurant").value;
        xmlhttp.open("GET","../includes/admin/exec-show-menu-item-info.php?id="+id+"&rId="+restaurantId,true);
        xmlhttp.send();
}

</script>
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

</head>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-md-9">
	<h2>Edit Menu Item Info</h2>
</div>
</div>
	<form action="../includes/admin/exec-edit-menu-item-info.php" enctype="multipart/form-data" method="post">

<div class="row"><div class="col-sm-3"><label>
	Restaurant:
	</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" name="serviceId" id="editMenuItemRestaurant" onchange="showMenuCategories(this.value, 'menuCategoriesEditItem');" required>
		<option disabled selected value> -- select an option -- </option>
	<?php
	echo displayServices($services);
	?>
</select> (restaurant for which this menu item is being added)</div></div><br>
<div class="row"><div class="col-sm-3"><label>
Menu Category:
</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" id="menuCategoriesEditItem" name="menuCategoryId" onchange="showMenuItems(this.value, 'menuItemEdit');" required>
	<option disabled selected value> -- select an option -- </option>
</select> (menu category with side to edit)</div></div><br>
<div class="row"><div class="col-sm-3"><label>
Menu Item:
</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" id="menuItemEdit" name="id" onchange="showMenuItemInfo(this.value);" required>
	<option disabled selected value> -- select an option -- </option>
</select> (menu item to edit)</div></div><br>

	<div id="menuItemInfo">
</div><div class="row"><div class="col-sm-12 offset-md-3 col-md-6"><input class="form-control btn btn-primary" type="submit"></div></div>
	</form>
</div>
