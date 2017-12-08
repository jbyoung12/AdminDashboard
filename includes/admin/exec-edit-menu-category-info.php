<?php
include(__DIR__.'/exec-increment-version-number.php');

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();


  if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){

DB::update('menu_categories', array(
  "name"=>$_POST["name"]), "id=%d", $_POST["menuCategoryId"]);

header("Location: /admin/index.php");
}
