<?php
include(__DIR__.'/exec-increment-version-number.php');

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

$id = $_GET['id'];

  if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
  DB::delete('category_items', "id=%d", $id);

  $hourId = DB::queryFirstField("SELECT id FROM item_hours WHERE restaurant_id=%d", $id);
  DB::delete('item_hours', "id=%d", $hourId);

  $menuSideIds = DB::queryFirstColumn("SELECT id from menu_sides where service_id='%d'", $id);
  DB::delete('menu_sides', "service_id=%d", $id);

  foreach ($menuSideIds as $sideId){
    DB::delete('menu_sides_item_link', "sides_id=%d", $sideId);
  }

  $menuCategoriesIds = DB::queryFirstColumn("SELECT id from menu_categories where service_id='%d'", $id);
  DB::delete('menu_categories', "service_id=%d", $id);
  foreach ($menuCategoriesIds as $menuCategoryId){
      DB::delete('menu_items', "category_id=%d", $menuCategoryId);
  }



header("Location: /admin/index.php");
}
