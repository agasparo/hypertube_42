<?php

require 'class/connexion.class.php';
require 'class/users.class.php';
require 'class/http.class.php';
require 'class/bdd.class.php';

if (!isset($_SESSION))
	session_start();


$co = new Oauth_42\connexion();
if ($co->success()) {

	require '../modules/bdd.php';

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

	$users = new Oauth_42\users();

	$user_login = $users->get_user_login();
	$name = $users->get_user_name();
	$surname = $users->get_user_surname();
	$mail = $users->get_user_mail();
	$lang = $users->get_user_lang();
	$api = 1;
	$img = $users->get_user_img();
	$mdp = create_pass(rand(23, 46));

	$bdd_req = new Oauth_42\bdd_reqs($bdd);

	if ($bdd_req->select([$user_login, 1]))
		$infs = $bdd_req->get_res();
	else {
		$bdd_req->insert([$user_login, $surname, $name, $mail, $mdp, $lang, $api], 1);
		$bdd_req->select([$user_login, 1]);
		$infs = $bdd_req->get_res();
		$bdd_req->insert([$infs['id'], $img], 0);
	}

	$_SESSION['id'] = $infs['id'];
	$_SESSION['lang'] = $infs['lang'];
	$_SESSION['token_42'] = "";
	header("Location:http://192.168.99.100.xip.io:41062/www/hypertube/");
}

?>