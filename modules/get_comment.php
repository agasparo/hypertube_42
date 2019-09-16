<?php

if (!isset($_SESSION)) {
	session_start();

	require '../modules/bdd.php';
	require '../panel/control.panel.php';

	$id = $_SESSION['id_film'];

	$url = '../template/comm.html';
} else {
	
	$url = 'template/comm.html';
}

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {


	$get_all_comment = $bdd->prepare('SELECT * FROM comm WHERE id_film = ? ORDER BY id DESC');
	$get_all_comment->execute(array($id));

	while ($get_comm = $get_all_comment->fetch()) {

		$user = $bdd->prepare('SELECT * FROM membre WHERE id = ?');
		$user->execute(array($get_comm['id_poster']));
		$userinfos = $user->fetch();

		$img = $bdd->prepare('SELECT * FROM img_users WHERE id_user = ?');
		$img->execute(array($userinfos['id']));
		$imginfos = $img->fetch();

		if (preg_match("#https://#", $imginfos['img']) || preg_match("#http://#", $imginfos['img']))
			$imgc = $imginfos['img'];
		else
			$imgc = '../photos/'.$imginfos['img'];

		$body = file_get_contents($url);
		$body = str_replace('{{id_user}}', $userinfos['id'], $body);
		$body = str_replace('{{nom_posteur}}', $userinfos['user_login'], $body);
		$body = str_replace('{{commentaire}}', $get_comm['comm'], $body);
		$body = str_replace('{{img_user}}', $imgc, $body);

		$time_passed = gmdate("j/m/Y H:i:s", $get_comm['date']);
		if ($post_on[strlen($post_on) - 1] != " ")
			$body = str_replace('{{time_passed}}', $post_on." ".$time_passed, $body);
		else
			$body = str_replace('{{time_passed}}', $post_on.$time_passed, $body);
		echo $body;
	}
}

?>