<?php

require '../modules/bdd.php';
require '../class/trie.class.php';
require '../panel/control.panel.php';

if (empty($_SESSION['id']) || !isset($_SESSION['id']))
	exit(0);

if (isset($id_page) AND !empty($id_page)) {
	$id_page = intval($id_page);
	if ($id_page == 0)
		$id_page = 1;
	$page_courrante = $id_page;
} else {
	$page_courrante = 1;
}

$depart = ($page_courrante -1)*$fims_par_page;

if (isset($_POST['serach_mov']) AND !empty($_POST['serach_mov']))
	$req = "WHERE name LIKE '%".addslashes($_POST['serach_mov'])."%'";
else
	$req = "";

if (isset($_POST['id']) AND !empty($_POST['id'])) {
	if (isset($_POST['type'])) {
		if ($_POST['type'] == 0)
			$order = 'ASC';
		else
			$order = 'DESC';
	} else
		$order = 'ASC';
	if ($_POST['id'] == 't_1')
		$add_req = "ORDER BY note ".$order;
	if ($_POST['id'] == 't_2')
		$add_req = "ORDER BY nb_vote ".$order;
	if ($_POST['id'] == 't_4')
		$add_req = "ORDER BY temps_films ".$order;
	if ($_POST['id'] == 't_5')
		$add_req = "ORDER BY name ".$order;

	if (isset($add_req))
		$trr = new trie($bdd, $req, $add_req, $depart, $fims_par_page, 1);
} else {
	if (isset($add_req) && !empty($add_req))
		$trr = new trie($bdd, $req, $add_req, $depart, $fims_par_page, 1);
	else
		$trr = new trie($bdd, $req, "ORDER BY name ASC", $depart, $fims_par_page, 1);
}

if (isset($_POST['id']) && $_POST['id'] == 't_3' && isset($_POST['type'])) {

	require 'date_prod.php';

	$films_infos = $_SESSION['all'];
} else {

	$films_infos = $trr->result();

	$_SESSION['all'] = $trr->get_tab();
}

$i = 0;

while (isset($films_infos[$i])) {
	$content = file_get_contents('../template/galerie.php');
	$content = str_replace('{{img_link}}', $films_infos[$i]['img'], $content);
	$last_visit = $bdd->prepare("SELECT * FROM vu_movies WHERE id_movie = ? AND id_user = ?");
	$last_visit->execute(array($films_infos[$i]['id'], $_SESSION['id']));
	if ($last_visit->rowCount() > 0)
		$content = str_replace('{{titre_film}}', $films_infos[$i]['name'].$vu, $content);
	else
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

if (empty($films_infos)) {

	$_SESSION['all'] = ["rr"];
	require '../class/request.class.php';
	require '../class/add_films.class.php';

	$req_film = new add_films(str_replace(" ", "-", $_POST['serach_mov']));
	$req_film->fetch($bdd, $no_res);
}
?>