<?php
require_once __DIR__.'/../all.php';

$cookies = new Cookies();
$user = $cookies->user_from_cookie();

$versionNumber = DB::query("SELECT VALUE FROM settings WHERE name=%s", 'version_number');
$versionNum = $versionNumber[0]['VALUE'] + 1;

DB::query("UPDATE settings SET value=$versionNum WHERE name=%s", 'version_number');
