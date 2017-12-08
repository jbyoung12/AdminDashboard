<?php

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$usermanager = new UserManager();
$user = $cookies->user_from_cookie();
if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){

  function generateHTMLFromData($data){
      $html = '';
      $start = $end = "";
      $fmt = "
          <tr>
          <td align='left'><a href='#info'>%s</a></td>
          <td align='left'><span>%s</span></td>
          <td align='left'><span>%s</span></td>
          <td align='left'><span>%s</span></td>
          <td align='left'>%s</td>
          </tr>
  </div>
          ";
  // order-address:         <div class='order-address'>%s<br>%s<br>%s, %s<br>%s</div>
  // order-name:         <div class='order-name'>%s<br>%s<br>%s</div>

      $html .= htmlLoop3($data, $start, $fmt, $end);
      return $html;
  }

  function getOrderForUser($user){
      $data = Array();
      $order = DB::query("SELECT * FROM orders ORDER BY time DESC LIMIT 60");
      date_default_timezone_set('UTC');
      $usersTimezone = new DateTimeZone('America/New_York');

      $categories = DB::query("SELECT * FROM category_items");
      foreach ($order as $o){
          $time   = $o["time"];
          $newDate = new DateTime($time);
          $newDate->setTimeZone($usersTimezone);
          $time = $newDate->format('Y-m-d h:i:s a');

          $catname = whereArray($categories, "id", $o["service_id"])["name"];
          $address = UserManager::getAddressFor($o["user_id"]);

          $user   = DB::queryOneRow("SELECT * FROM accounts WHERE uid=%s", $o["user_id"]);
          $items = UserManager::getItemsForCart($o["user_id"], $o["id"]);

          $address_str = sprintf("%s (%s) <br>%s, %s. %s", $address["street"], $address["apartment"], $address["city"], $address["state"], $address["zip"]);

          $odata = Array($o['id'], $time, $catname, $user["name"], $address_str, $items["html"], $items["price"]);
          array_push($data, $odata);
      }
      return $data;
  }
  $data = getOrderForUser($user);
  if (count($data) == 0){
      echo "No orders";
  }else{
      echo generateHTMLFromData($data);
  }
}
