<?php

session_start();
extract($_POST);

function mentionnerutil($matchs) {
	global $bdd;
	$req_user = $bdd->prepare('SELECT id FROM membre WHERE user_login = ?');
	$req_user->execute(array($matchs[1]));
	if ($req_user->rowCount() == 1) {
		$id_user = $req_user->fetch()['id'];
		return ("<a href='../user/".$id_user."'>".$matchs[0]."</a>");
	}
	return ($matchs[0]);
}

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
	
	require 'bdd.php';

	if (isset($mess) && !empty($mess)) {

		$id = $_SESSION['id_film'];

		$msg = htmlspecialchars($mess);
		$msg = str_replace(":)", "&#128512;", $msg);
		$msg = str_replace("^^", "&#128513;", $msg);
		$msg = str_replace(":')", "&#128514;", $msg);
		$msg = str_replace(";)", "&#128527;", $msg);
		$msg = str_replace("(-_-)", "&#128542;", $msg);
		$msg = str_replace("&lt;3", "&hearts;", $msg);
		$msg = str_replace("8)", "&#129299;", $msg);
		$msg = str_replace(":p", "&#128539;", $msg);
		$msg = str_replace(";p", "&#128540;", $msg);
		$msg = str_replace(":(", "&#128577;", $msg);
		$msg = str_replace(":o", "&#128559;", $msg);

		$msg_final = preg_replace_callback("#@([A-Za-z0-9_]+)#", 'mentionnerutil', $msg);

		$insert_msg = $bdd->prepare('INSERT INTO comm(id_film, id_poster, comm, date) VALUES (?, ?, ?, ?)');
		$insert_msg->execute(array($id, $_SESSION['id'], $msg_final, time()));
	}
}

?>