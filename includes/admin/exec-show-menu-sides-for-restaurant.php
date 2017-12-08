<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();
$id = $_GET['id'];
#$num = $_GET['num'];

$menuSides = DB::query("SELECT ID, NAME FROM menu_sides WHERE service_id='%d' ORDER BY NAME", $id);

$htmlCode = '<option disabled selected value> -- select an option -- </option>';
foreach ($menuSides as $row) {
  $htmlCode .= '<option value="'.$row['ID'].'">'.$row['NAME'].'</option>';
}

// for ($i=0; $i<$num; $i++){
//
// }
echo $htmlCode;
