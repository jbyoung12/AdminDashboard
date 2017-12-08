<?php
include(__DIR__.'/exec-increment-version-number.php');

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();


  if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){

DB::update('menu_sides', array(
  "name"=>$_POST["name"], "price"=>$_POST["price"], "required"=>$_POST["required"], "grouping"=>$_POST["grouping"]), "id=%d", $_POST["id"]);

header("Location: /admin/index.php");
}
