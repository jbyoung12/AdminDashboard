<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();
$id = $_GET['id'];

$menuCategories = DB::query("SELECT ID, NAME FROM menu_categories WHERE service_id='%d' ORDER BY NAME", $id);

$htmlCode = '<option disabled selected value> -- select an option -- </option>';

foreach ($menuCategories as $row) {
  $htmlCode .= '<option value="'.$row['ID'].'">'.$row['NAME'].'</option>';
}
echo $htmlCode;
