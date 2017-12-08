<?php
include(__DIR__.'/exec-increment-version-number.php');

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

$vars = array("name","price","required","serviceId","grouping");

if (set_vars($_POST, $vars)){
    error_log(var_export($_POST, true));
    error_log(var_export($user, true));
    if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){

        $result = DB::insert("menu_sides", array("name"=>$_POST["name"], "price"=>$_POST["price"],
        "required"=>$_POST["required"], "service_id"=>$_POST["serviceId"], "grouping"=>$_POST["grouping"]));

        header("Location: /admin/index.php");
    }
    else{
        echo "-1";
    }
}
else{
    echo "-1";
}
