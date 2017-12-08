<?php
require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();
$id = $_GET['id'];
#$num = $_GET['num'];

$restaurantRow = DB::queryFirstRow("SELECT * FROM menu_categories WHERE id='%d'", $id);

$htmlCode = '<div class="row">
<div class="col-sm-3"><label>
Name of Menu Category: </label></div><div class="col-sm-9 col-md-6">
<input class="form-control" type="text" name="name" value="'.$restaurantRow['name'].'" required></div></div><br>';

echo $htmlCode;
