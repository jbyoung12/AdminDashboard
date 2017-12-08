<?php
include(__DIR__.'/exec-increment-version-number.php');


require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

$vars = array("name","serviceId");
DB::query("SELECT * FROM menu_categories WHERE `service_id`=%d", $_POST["serviceId"]);
$orderPosition = DB::count();
$orderPosition = $orderPosition + 1;

if (set_vars($_POST, $vars)){
    error_log(var_export($_POST, true));
    error_log(var_export($user, true));
    if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){

        $result = DB::insert("menu_categories", array("category_id"=>"1", "name"=>$_POST["name"],
        "service_id"=>$_POST["serviceId"], "displayorder"=>$orderPosition));

        header("Location: /admin/index.php");
    }
    else{
        echo "-1";
    }
}
else{
    echo "-1";
}
