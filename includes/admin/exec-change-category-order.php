<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

$vars = array('service_id', 'category_id', 'display_order');
if (set_vars($_POST, $vars)){
    if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
        $original = DB::queryOneRow('SELECT * FROM menu_categories WHERE service_id=%d AND id=%d', $_POST['service_id'], $_POST['category_id']);
        $replaced = DB::queryOneRow('SELECT * FROM menu_categories WHERE service_id=%d AND displayorder=%d', $_POST['service_id'], $_POST['display_order']);

        DB::update('menu_categories', array('displayorder' => $_POST['display_order']), 'id=%s', $original['id']);
        DB::update('menu_categories', array('displayorder' => $original['displayorder']), 'id=%s', $replaced['id']);

        echo DB::affectedRows();
    }else{
        echo "-1";
    }
}else{
    echo "-1";
}
