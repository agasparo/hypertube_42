<?php

session_start();
require 'bdd.php';
require '../class/convert_subtitle.class.php';

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {

	if(isset($_FILES['add_subs']) AND !empty($_FILES['add_subs']['name'])) {

		if (isset($_POST['lang_val']) && isset($_POST['name_val']) && !empty($_POST['lang_val']) && !empty($_POST['name_val'])) {
			$lang = htmlspecialchars($_POST['lang_val']);
			$witch_film = $bdd->prepare('SELECT * FROM films WHERE name = ?');
			$witch_film->execute(array(htmlspecialchars($_POST['name_val'])));
			if ($witch_film->rowCount() > 0) {
				$ing = $witch_film->fetch();
				$id_film = $ing['id'];
				$tailleMax = 2097152;
				$extensionsValides = ['vtt', 'srt'];
				if($_FILES['add_subs']['size'] <= $tailleMax) {
					$ext = pathinfo($_FILES['add_subs']['name'], PATHINFO_EXTENSION);
					if(in_array($ext, $extensionsValides)) {
						$chemin = "../subtitles/".$id_film."_".$lang.".vtt";
						if ($ext == 'srt')
							$chemin1 = "../subtitles/".$id_film."_".$lang.".".$ext;
						else
							$chemin1 = $chemin;
						if (!file_exists($chemin)) {
							$resultat = move_uploaded_file($_FILES['add_subs']['tmp_name'], $chemin1);
							if ($resultat) {
								if ($ext == 'srt') {
									$convert = new convert_subtitle();
									$convert->convert($chemin1);
								}
								$insert_sub = $bdd->prepare("INSERT INTO sub(id_user, id_film, time_s, path, lang, film_name) VALUES(?, ?, now(), ?, ?, ?)");
								$insert_sub->execute(array($_SESSION['id'], $id_film, $chemin, $lang, htmlspecialchars($_POST['name_val'])));
							}
						}
					}
				}
			}
		}
	}
	$url = "../user/".$_SESSION['id'];
	?><meta http-equiv='refresh' content='0;URL=<?= $url; ?>'><?php
}
?>