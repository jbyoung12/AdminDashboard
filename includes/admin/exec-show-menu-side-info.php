<?php
require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();
$id = $_GET['id'];
#$num = $_GET['num'];

$restaurantRow = DB::queryFirstRow("SELECT * FROM menu_sides WHERE id='%d'", $id);

$htmlCode = '<div class="row"><div class="col-sm-3"><label>Name of Menu Side: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="text" name="name" value="'.$restaurantRow['name'].'" required></div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Price: </label></div><div class="col-sm-9 col-md-6"><input class="form-control" type="number" name="price" value="'.$restaurantRow['price'].'" min="0" step=".01" required> (0 if free)</div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Required? </label></div><div class="col-sm-9 col-md-6"><select class="form-control" name="required" required>
  <option value="0" '.($restaurantRow['required'] != 1 ? 'selected' : '').'>No</option>
  <option value="1" '.($restaurantRow['required'] == 1 ? 'selected' : '').'>Yes</option>
</select> (Is this menu item a required side or an extra?)</div></div><br>';
$htmlCode .= '<div class="row"><div class="col-sm-3"><label>Grouping (Not Required): </label></div><div class="col-sm-9 col-md-6"><input type="text" value="'.$restaurantRow['grouping'].'" name="grouping" class="form-control notRequiredFormatter"> (Should this menu side appear in a grouping (e.g. rice, protein, sauce, etc.)? If so, list it here)
</div></div><br>';

echo $htmlCode;
