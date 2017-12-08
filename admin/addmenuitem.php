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

function showSides(id) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("menuSides").innerHTML = this.responseText;
								$(".ajaxmultiSelect").multipleSelect({
									 width: $(".ajaxmultiSelect").parent().width(),
									 multiple: true,
									 multipleWidth: 250
							 });
            }
        };
        // xmlhttp.open("GET","/includes/admin/exec-show-menu-sides-for-restaurant.php?id="+id+"&num="+num,true);
				xmlhttp.open("GET","/includes/admin/exec-show-menu-sides-for-restaurant.php?id="+id,true);
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

 <!--[if lte IE 9]><link rel="stylesheet" href="../css/ie/v9.css" /><![endif]-->
 <!--[if lte IE 8]><link rel="stylesheet" href="../css/ie/v8.css" /><![endif]-->
</head>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-md-9">
	<h2>Add Menu Item</h2>
</div>
</div>
	<form action="../includes/admin/exec-add-menu-item.php" enctype="multipart/form-data" method="post">

<div class="row"><div class="col-sm-3"><label>
		Restaurant:
		</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" name="serviceId" onchange="showMenuCategories(this.value, 'menuCategories'); showSides(this.value);" required>
			<option disabled selected value> -- select an option -- </option>
		<?php
		echo displayServices($services);
		?>
	</select> (restaurant for which this menu item is being added)</div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Menu Category:
	</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" id="menuCategories" name="menuCategoryId" required>
	</select> (menu category for which this menu item is being added)</div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Name of Menu Item: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="name" required></div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Description: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="description" required> (description of the menu item and its ingredients)</div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Price of Menu Item: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="number" name="price" min="0" step=".01" required></div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Add Sides: (the sides connected to this menu item)
</label></div><div class="col-sm-9 col-md-6"> <select style="display:none" id="menuSides" class="ajaxmultiSelect" name="sidesIds[]" multiple="multiple">
	</select></div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Number of Sides: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="number" name="numSides" min="0" max="15" required></div></div><br>
<div class="row"><div class="col-sm-3"><label>
	Featured?
	</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" name="isFeatured" required>
		<option value="0">No</option>
		<option value="1">Yes</option>
	</select> (Do you want to feature this item as one of the top items in the restaurant?)
</div></div><br><input class="form-control" type="hidden" name="MAX_FILE_SIZE" value="512000" />
	<div class="row"><div class="col-sm-3"><label>
	Featured Image (If featured, this is REQUIRED):</label></div><div class="col-sm-9 col-md-6"><input name="image" type="file" class="notRequiredFormatter form-control"/></div></div><br>
	<div class="row"><div class="col-sm-12 offset-md-3 col-md-6"><input class="form-control btn btn-primary" type="submit"></div></div>
	</form>
</div>
