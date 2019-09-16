<?php

if (!isset($_SESSION))
	session_start();

require 'vendor/match.class.php';

//***********************Connection a l api*****************************//
$url_retour = 'http://192.168.99.100:41062/www/hypertube/matcha_connexion/api.php';
$macth_init = new Match_connection();
if (isset($_GET['acces_token']) && !empty($_GET['acces_token'])) {
	$_SESSION['acces_token_matcha'] = $_GET['acces_token'];
	$_SESSION['pass_token'] = $_GET['pass_token'];
}
if (!isset($_SESSION['acces_token_matcha']) || empty($_SESSION['acces_token_matcha'])) {
	$url = 'http://192.168.99.100.xip.io:41062/www/matcha_42/api/connection.php?url='.urlencode($url_retour);
	echo "<a href='".$url."'><img src='matcha_connexion/img/logo.png' style='width:8vw;'></a>";
} else {
	require '../modules/bdd.php';
	//***********************Connection a lespace user*****************************//
	$user = $macth_init->ini_user();
	$a = $user->connect_user();

	$pseudo = $user->get_user_prenom($a)."_".$user->get_user_nom($a);
	$check = $bdd->prepare('SELECT * FROM membre WHERE user_login = ? AND api = ?');
	$check->execute(array($pseudo, 1));
	$infs = $check->fetch();
	if ($check->rowCount() == 0) {
		$insert_user = $bdd->prepare('INSERT INTO membre(user_login, prenom, nom, mail, password, lang, api) VALUES(?, ?, ?, ?, ?, ?, ?)');
		$insert_user->execute(array($pseudo, $user->get_user_prenom($a), $user->get_user_nom($a), $user->get_user_mail($a), create_pass(rand(23, 46)), "en_US", 1));
		$new_val = $bdd->prepare('SELECT * FROM membre WHERE user_login = ? AND api = ?');
		$new_val->execute(array($pseudo, 1));
		$infs = $new_val->fetch();
		$inser_photo = $bdd->prepare('INSERT INTO img_users(id_user, img) VALUES(?, ?)');
		$inser_photo->execute(array($infs['id'], $user->get_user_photos($a)));
	}
	$_SESSION['id'] = $infs['id'];
	$_SESSION['lang'] = $infs['lang'];
	?><meta http-equiv="refresh" content="0;URL=../å"><?php
}

function create_pass($length) {

    require '../class/crypt.class.php';

    $e = new crypt();

    $caracteres = 'abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longueurMax = strlen($caracteres);
    $chaineAleatoire = '';
    $i = 0;
    while ($i < $length) {
      $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
      $i++;
    }
    return ($e->encrypt($chaineAleatoire));
  }
?>