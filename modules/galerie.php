<?php

require 'modules/bdd.php';
require 'class/trie.class.php';

if (isset($id_page) AND !empty($id_page)) {
	$id_page = intval($id_page);
	if ($id_page == 0)
		$id_page = 1;
	$page_courrante = $id_page;
} else {
	$page_courrante = 1;
}

if ($film_total < $id_page)
	$page_courrante = 1;

$depart = ($page_courrante -1)*$fims_par_page;

$show_film = "ok";

if (isset($_SESSION['all']) && !empty($_SESSION['all']) && is_array($_SESSION['all'])) {
	$trr = new trie("", "", "", $depart, $fims_par_page, 0);
	$films_infos = $trr->with_tab($_SESSION['all'], $depart, intval($fims_par_page + $depart));
} else
	$show_film = $bdd->query('SELECT * FROM films LIMIT '.$depart.','.$fims_par_page);

$i = 0;
while (isset($films_infos[$i])) {
	$content = file_get_contents('template/galerie.php');
	$content = str_replace('{{img_link}}', $films_infos[$i]['img'], $content);
	if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
		$last_visit = $bdd->prepare("SELECT * FROM vu_movies WHERE id_movie = ? AND id_user = ?");
		$last_visit->execute(array($films_infos[$i]['id'], $_SESSION['id']));
		if ($last_visit->rowCount() > 0)
			$content = str_replace('{{titre_film}}', $films_infos[$i]['name'].$vu, $content);
		else
			$content = str_replace('{{titre_film}}', $films_infos[$i]['name'], $content);
	} else
	$content = str_replace('{{titre_film}}', $films_infos[$i]['name'], $content);
	$content = str_replace('{{id_film}}', $films_infos[$i]['id'], $content);
	$notes = intval($films_infos[$i]['note']);
	$note = '<div class="rating" id="'.$films_infos[$i]['id'].'_note_re">';
	$a = 0;
	while ($a < $notes) {
		$note.= '<a href="#" id="'.$films_infos[$i]['id'].'_'.$a.'" class="note active_note">☆</a>';
		$a++;
	}
	while ($a < 10) {
		$note.= '<a href="#" id="'.$films_infos[$i]['id'].'_'.$a.'" class="note not_active_note">☆</a>';
		$a++;
	}
	$note .= '</div>';
	$content = str_replace('{{note_film}}', $note, $content);
	$content = str_replace('{{s_film}}', $s_film, $content);
	echo $content;
	$i++;
}

if ($show_film != "ok") {
	while ($films_infos = $show_film->fetch()) {
		$content = file_get_contents('template/galerie.php');
		$content = str_replace('{{img_link}}', $films_infos['img'], $content);
		if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
			$last_visit = $bdd->prepare("SELECT * FROM vu_movies WHERE id_movie = ? AND id_user = ?");
			$last_visit->execute(array($films_infos['id'], $_SESSION['id']));
			if ($last_visit->rowCount() > 0)
				$content = str_replace('{{titre_film}}', $films_infos['name'].$vu, $content);
			else
				$content = str_replace('{{titre_film}}', $films_infos['name'], $content);
		} else
		$content = str_replace('{{titre_film}}', $films_infos['name'], $content);
		$content = str_replace('{{id_film}}', $films_infos['id'], $content);
		$notes = intval($films_infos['note']);
		$note = '<div class="rating" id="'.$films_infos['id'].'_note_re">';
		$a = 0;
		while ($a < $notes) {
			$note.= '<a href="#" id="'.$films_infos['id'].'_'.$a.'" class="note active_note">☆</a>';
			$a++;
		}
		while ($a < 10) {
			$note.= '<a href="#" id="'.$films_infos['id'].'_'.$a.'" class="note not_active_note">☆</a>';
			$a++;
		}
		$note .= '</div>';
		$content = str_replace('{{note_film}}', $note, $content);
		$content = str_replace('{{s_film}}', $s_film, $content);
		echo $content;
	}
}
?>