<?php
include(__DIR__.'/exec-increment-version-number.php');

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

$ids = $_GET['id'];

  if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
foreach ($ids as $id){
  DB::delete('menu_categories', "id=%d", $id);
  $menuItemIds = DB::queryFirstColumn("SELECT id from menu_items where category_id='%d'", $id);
  DB::delete('menu_items', "category_id=%d", $id);
    foreach ($menuItemIds as $itemId){
    DB::delete('menu_sides_item_link', "item_id=%d", $itemId);
  }
}

header("Location: /admin/index.php");
}
