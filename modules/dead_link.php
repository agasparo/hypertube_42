<?php

require '../modules/bdd.php';

session_start();

if (isset($_SESSION['id']) && !empty($_SESSION['id']) && isset($_SESSION['id_film']) && !empty($_SESSION['id_film'])) {

	$is = $bdd->prepare("SELECT * FROM signaler WHERE id_user = ? AND id_film = ?");
	$is->execute([$_SESSION['id'], $_SESSION['id_film']]);

	if ($is->rowCount() == 0) {

		$insert_s = $bdd->prepare("INSERT INTO signaler(id_user, id_film) VALUES(?, ?)");
		$insert_s->execute([$_SESSION['id'], $_SESSION['id_film']]);
	}

	header("Location:../films/".$_SESSION['id_film']);
} else
	header("Location:http://192.168.99.100.xip.io:41062/www/hypertube/");

?>