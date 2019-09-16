<?php

require '../modules/bdd.php';
require '../panel/control.panel.php';

if (!isset($_SESSION))
	session_start();
extract($_POST);

if (isset($link)) {

	$decoded = base64_decode(substr($link, 22));
	$fichier = uniqid().'.png';
	$fd = fopen("../photos/".$fichier, "w+");
	file_put_contents("../photos/".$fichier, $decoded);
	fclose($fd);


	if (isset($_SESSION['connect_img']) && !empty($_SESSION['connect_img'])) {

		$req_user = $bdd->prepare("SELECT * FROM membre WHERE user_login = ?");
		$req_user->execute(array($_SESSION['connect_img']));
		$user = $req_user->fetch();
	}

	if (isset($user))
		$co = $user['id'];
	else
		$co = $_SESSION['id'];

	$is = $bdd->prepare("SELECT * FROM img_users WHERE id = ?");
	$is->execute([$co]);

	if ($is->rowCount() == 0) {

		$req_img = $bdd->prepare('INSERT INTO img_users(id_user, img) VALUES(?, ?)');
		$req_img->execute(array($co, $fichier));
	} else {

		$req_img = $bdd->prepare('UPDATE img_users SET img = ? WHERE id = ?');
		$req_img->execute(array($fichier, $co));
	}

	$rep_tab['success'] = 1;
	$rep_tab['infos'] = "<img src ='photos/".$fichier."' class='img_upload'/>";
	echo json_encode($rep_tab);
}
?>