<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

//$vars = array("name","category_id","service_id");
//if (set_vars($_POST, $vars)){
if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
    $id = $_POST["id"];
    $arr = array_filter($_POST, function($v){return $v !== '';});
    if ($item = DB::queryOneRow('select * from menu_items where id=%s', $id)) {
        unset($arr['id']);
        DB::update('menu_items', $arr, 'id=%s', $id);
    }
    else {
        DB::insert('menu_items', $arr);
    }

    $iid = DB::insertId();
    $old_iid = intval(DB::queryOneRow("SELECT value FROM settings WHERE name='lastitem'")["value"]);
    if ($iid > $old_iid){
        DB::update("settings", array("value"=>$iid), "name=%s", "lastitem");
    }
    echo DB::insertId();
}
else{
    echo "-1";
}
//}
//else{
//    echo "-1";
//}
