<?php

require "vendor/autoload.php";

if (file_exists('modules/bdd.php'))
	require_once('modules/bdd.php');
else
	require_once('../modules/bdd.php');

if (!isset($_SESSION))
	session_start();

$auth = new Instagram\Auth([
	'client_id'     => '',
	'client_secret' => '',
	'redirect_uri'  => 'http://192.168.99.100.xip.io:41062/www/hypertube/insta_connexion/index.php'
]);

if(!isset($_SESSION['instagram_token'])){
	if(!isset($_GET['code'])){
		?><a href="<?= $auth->authorize(); ?>"><img src="insta_connexion/img/logo.png" style='width:10vw;'></a><?php
	} else {
		$access_token = $auth->getAccessToken($_GET['code']);
		$_SESSION['instagram_token'] = $access_token;
	}
}

if (isset($_SESSION['instagram_token'])) {
	require 'Instagram/membres.class.php';

	$instagram = new Instagram\Instagram();
	$instagram->setAccessToken($_SESSION['instagram_token']);
	$current_user = $instagram->getCurrentUser();

	$user_login = $current_user->getFullName()." ";
	$user_picture_pro = $current_user->getProfilePicture()." ";

	$user = new Instagram\membres($user_login, $bdd);
	$_SESSION['id'] = $user->exec_member($user_picture_pro, $bdd);
	$_SESSION['lang'] = $user->get_user_lang();
	?><meta http-equiv="refresh" content="0;URL=../"><?php
}

?>