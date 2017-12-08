<?php
require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();


$email = $_GET['email'];

  if ($user->data["permission"] === "4" || ($user->data["permission"] === "3" && $user->data["service_id"] === $_POST["service_id"])){
DB::update('accounts', array(
  'permission' => '4',
), "email=%s", $email);

header("Location: /admin/index.php");
}
