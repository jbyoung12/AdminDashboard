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

function showMenuSideInfo(id) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("menuSideInfo").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","../includes/admin/exec-show-menu-side-info.php?id="+id,true);
        xmlhttp.send();
}

function showMenuSides(id, elementId) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(elementId).innerHTML = this.responseText;
								$(".deleteMenuSides").multipleSelect({
									 width: $(".deleteMenuSides").parent().width(),
									 multiple: true,
									 multipleWidth: 250
							 });
            }
        };
        xmlhttp.open("GET","../includes/admin/exec-show-menu-sides-for-restaurant.php?id="+id,true);
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
	<h2>Edit Menu Side Info</h2>
</div>
</div>
	<form action="../includes/admin/exec-edit-menu-side-info.php" method="post">

<div class="row"><div class="col-sm-3"><label>
	Restaurant:
	</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" name="serviceId" onchange="showMenuSides(this.value, 'menuSidesEdit');" required>
		<option disabled selected value> -- select an option -- </option>
	<?php
	echo displayServices($services);
	?>
</select> (restaurant for which this menu item is being added)</div></div><br>
<div class="row"><div class="col-sm-3"><label>
Menu Sides:
</label></div><div class="col-sm-9 col-md-6"> <select class="form-control" id="menuSidesEdit" name="id" onchange="showMenuSideInfo(this.value);" required>
	<option disabled selected value> -- select an option -- </option>
</select> (menu side to edit)</div></div><br>
	<div id="menuSideInfo">
	</div>
	<div class="row"><div class="col-sm-12 offset-md-3 col-md-6"><input class="form-control btn btn-primary" type="submit"></div></div>
	</form>
</div>
