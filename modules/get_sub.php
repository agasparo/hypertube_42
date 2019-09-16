<?php

session_start();
require '../modules/bdd.php';


if (isset($_SESSION['id']) && !empty($_SESSION['id']) && isset($_SESSION['id_film']) && !empty($_SESSION['id_film'])) {
	$get_sub = $bdd->prepare("SELECT * FROM sub WHERE id_film = ?");
	$get_sub->execute(array($_SESSION['id_film']));

	$data = [];
	$i = 0;
	while ($sub = $get_sub->fetch()) {
		$data['lang'][$i] = $sub['lang'];
		$data['path'][$i] = $sub['path'];
		$i++;
	}
	$data['count'] = $i;
	echo json_encode($data);
}

?>