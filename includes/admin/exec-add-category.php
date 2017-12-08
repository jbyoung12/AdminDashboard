<?php
include(__DIR__.'/exec-increment-version-number.php');

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

$vars = array("name","service_id");
if (set_vars($_POST, $vars)){
    error_log(var_export($_POST, true));
    error_log(var_export($user, true));
    if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
        $existing = DB::query('SELECT id FROM menu_categories WHERE service_id=%d', $_POST['service_id']);
        $displayorder = DB::count() + 1;

        DB::insert("menu_categories", array("category_id"=>$_POST["service_id"], "service_id"=>$_POST["service_id"], "name"=>$_POST["name"], 'displayorder' => $displayorder));
        echo DB::insertId();
    }
    else{
        echo "-1";
    }
}
else{
    echo "-1";
}
