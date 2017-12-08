<?php

require_once __DIR__.'/../all.php';

$type = $_POST["type"];
$f = $_FILES['imgfile'];

if ($type == "logo"){
    $imgtype="image";
}
else if ($tpe == "cover"){
    $imgtype="order_image";
}

$loc = uploadImage($f);
if ($loc !== -1){
    DB::update("category_items",array($imgtype=>$loc), "id=%s", $_POST["sid"]);
}
//echo "<img src='/images/" . $loc . "'>";
header("Location: /admin/index.php?p=3");
exit;
