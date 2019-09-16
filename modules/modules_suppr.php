<?php

require '../modules/bdd.php';

session_start();

extract($_POST);

if (isset($src) && isset($video) && !empty($src) && isset($_SESSION['id']) && !empty($_SESSION['id'])) {
	$url = explode("/", $src);
	$link = $url[count($url) - 1];
	unlink("../tmp/".$link);

	$is = $bdd->prepare("SELECT * FROM time_f WHERE id_user = ? AND id_film = ?");
	$is->execute([$_SESSION['id'], $_SESSION['id_film']]);
	if ($is->rowCount() == 0) {

		$insert_time = $bdd->prepare("INSERT INTO time_f(id_user, id_film, time) VALUES(?, ?, ?)");
		$insert_time->execute([$_SESSION['id'], $_SESSION['id_film'], $video]);
	} else {

		$update = $bdd->prepare("UPDATE time_f SET time = ? WHERE id_user = ? AND id_film = ?");
		$update->execute([$video, $_SESSION['id'], $_SESSION['id_film']]);
	}
}

?>