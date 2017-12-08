<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();
$vars = array("id");
if (set_vars($_POST, $vars) && $user !== 0){
    DB::delete("category_items", "id=%s", $_POST["id"]);
}
header("Location: /admin/index.php?p=3");
exit;
