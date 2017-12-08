<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

$vars = array("id","service_id");
if (set_vars($_POST, $vars)){
    if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
//        DB::insert("menu_sides", array("name"=>$_POST["name"], "price"=>$_POST["price"], "required"=>$_POST["req"], "service_id"=>$_POST["service_id"]));
        DB::delete("menu_items", "id=%d", $_POST["id"]);
        echo DB::affectedRows();
    }
    else{
        echo "-1";
    }
}
else{
    echo "-1";
}
