<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
    $id = $_POST["id"];
    $hours = [
        'open_hours' => sanitizeHours($_POST['hours']),
        'timezone' => $_POST['timezone'],
        'restaurant_id' => $id,
    ];

    unset($_POST["id"]);
    unset($_POST["timezone"]);
    unset($_POST["hours"]);

    $arr = array_filter($_POST, function($v){return $v !== '';});

    if ($id > 0) {
        if (!empty($arr)) {
            DB::update("category_items", $arr, "id=%d", $id);
        }

        if (!empty($hours['open_hours'])) {
            $matches = DB::query('SELECT id FROM item_hours WHERE restaurant_id=%d', $id);
            if (!empty($matches)) {
                DB::update('item_hours', $hours, 'restaurant_id=%d', $id);
            }
            else {
                DB::insert('item_hours', $hours);
            }
        }
    } else {
        DB::insert("category_items", $arr);
        $id = DB::insertId();

        $hours['restaurant_id'] = $id;
        DB::insert('item_hours', $hours);
    }

    echo $id;

}
else{
    echo "-1";
}
