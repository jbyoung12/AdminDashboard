<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$usermanager = new UserManager();
$user = $cookies->user_from_cookie();

$vars = array("0","1","2","3");

function sortArrayBy($data, $k){
    foreach ($data as $key => $row) {
        $sortBy[$key]  = $row[$k];
    }
    // Sort the data with volume descending, edition ascending
    // Add $data as the last parameter, to sort by the common key
    array_multisort($sortBy, SORT_DESC, $data);
}

function returnOrderedFeed($t, $s, $f, $c){
    if ($t == -1 | $s == -1 | $c == -1){
        return "";
    }
    $service = DB::queryOneRow("SELECT id FROM categories WHERE id=%s", $s);

    $companies = DB::query("SELECT name, id, category_id FROM category_items WHERE category_id=%s AND id=%d", $service["id"], $c);
    $orders = array();
    $now = new DateTime("now");

    $addressCache = array();

    foreach ($companies as $company){
        if ($company["name"])
            $q = DB::query("SELECT * FROM orders WHERE service_id=%s AND category_id=%s", $company["category_id"], $company["id"]);
        foreach ($q as $order){
            if (!array_key_exists($order["user_id"], $addressCache)){
                $addressCache[$order["user_id"]] = UserManager::getAddressFor($order["user_id"]);
            }
            $addr = refineArray(array($addressCache[$order["user_id"]]), array("street","apartment","city","state","zip"))[0];
            $di = $now->diff(new DateTime($order["time"]));
            if ($di->d <= $t){
                $items = UserManager::getItemsForCart($order["user_id"], $order["id"]);   //$order["cart_id"]);
                $userdata = DB::queryOneRow("SELECT * FROM accounts WHERE uid=%s", $order["user_id"]);
                $userdata = refineArray(array($userdata), array("name","email","phone"))[0];

                $nonOrderedOrder = array_merge($order, array_merge(array("compName"=>$company["name"], "items"=>$items["html"], "price"=>$items["price"]), $addr, $userdata));
                $sortOrder = array("id", "time","compName",5,6,7,0,1,2,3,4, "items","price");
                $nonOrderedOrder_trimmed = refineArray(array($nonOrderedOrder), $sortOrder)[0];
//                time, restname, username, email, phone, street, apt, city, state, zip, items, total
//                               array_multisort($nonOrderedOrder_trimmed, $sortOrder);
                $orderedOrder = $nonOrderedOrder_trimmed;

                $orderedOrder["category_id"] = $nonOrderedOrder["category_id"];
                $orderedOrder["campus"] = $nonOrderedOrder["campus"];
                $orderedOrder["timeSince"] = $di->d;
                $orders[] = $orderedOrder;
            }
        }

    }
    // sort output
    usort($orders, function($a, $b) {
        $a_time = $a[1];
        $b_time = $b[1];

        if ($a == $b) {
            return 0;
        }
        // sort in descending order
        return ($a < $b) ? 1 : -1;
    });
    $orders = refineArrayReductively($orders, array("category_id","campus","timeSince"));
    return $orders;
    // t = time, s = service, f = filter, c = company

}



function generateHTMLFromData($data){
    $html = '';
    $start = $end = "";
    $fmt = "
        <div class='order-info'>
            <div class='order-id'>Order ID: %s</div>
            <div class='order-time'>Order Time: %s</div>
            <div class='order-restaurant'>Restaurant: %s</div>
            <div class='order-name'>Customer: %s<br>%s<br>%s</div>
            <div class='order-address'>Address: %s<br>%s<br>%s, %s<br>%s</div>
            <div class='order-items'>%s</div>
            <div class='order-total'><span class='pretotal'>TOTAL </span> %s</div>
            <br><br>
        </div>
        ";
    $html .= htmlLoop($data, $start, $fmt, $end);
    return $html;
}

if (set_vars($_POST, $vars)){
    $orderData = returnOrderedFeed(intval($_POST["0"]), intval($_POST["1"]), intval($_POST["2"]), intval($_POST["3"]));
    if ($orderData === ""){
        echo json_array(0);
    }
    else{
        $orderHTML = generateHTMLFromData($orderData);
        echo json_array(1, $orderHTML);
    }
//    return $user->add_address($_POST["street"], $_POST["apartment"], $_POST["city"], $_POST["state"], $_POST["zipcode"]);
}
else{
    echo json_array(0);
}
