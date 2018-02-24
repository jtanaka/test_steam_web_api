<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
include_once("../vendor/autoload.php");

$openid = new LightOpenID("https://test-steam-web-api.azurewebsites.net");
$openid->identity = "http://steamcommunity.com/openid";
$openid->returnUrl = "https://test-steam-web-api.azurewebsites.net/auth.php";

if (!$openid->mode) {
	header('Location: ' . $openid->authUrl());
} elseif ($openid->mode == 'cancel') {
	header('Location: /');
} else {
	if ($openid->validate()) {
		session_start();

		$claimed = explode("http://steamcommunity.com/openid/id/", $openid->identity);
		$steam_id = $claimed[1];
		$_SESSION['steam_id'] = $steam_id;
	}

	header('Location: /');
}

exit;