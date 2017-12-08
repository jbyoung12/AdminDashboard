<?php
include(__DIR__.'/exec-increment-version-number.php');

require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();


$alertMessage = $_GET['alertMessage'];
$alertMessageApp = $_GET['alertMessageApp'];
$color = $_GET{'color'};
$link = $_GET{'link'};

if ($color == '')
{
  $color = '#FF5959';
}

  if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
// DB::query("UPDATE settings SET value=>$alertMessage WHERE name='%s'", 'alert_message');
DB::update('settings', array(
  'value' => $alertMessage,
  'display' => $color,
  'extra' => $link
), "name=%s", 'alert_message_web');

DB::update('settings', array(
  'value' => $alertMessageApp
), "name=%s", 'alert_message');


header("Location: /admin/index.php");
}
