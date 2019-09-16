<?php

if (isset($bdd) && isset($id)) {
	$films = new films();
	$films->set_infos($id, $bdd);
	$film_name = $films->get_name();
	$film_img_cover = $films->get_img_cover();
	$film_realisateur = $films->get_realisateur();
	$film_rcasting = $films->get_casting();
	$film_prod = $films->get_date_prod();
	$film_resu = $films->get_resumer();
	$film_temps = $films->get_temps_films();
	$film_note = $films->get_note($note_ob);
	$film_vote = $films->get_vote();
	$film_dowload = $films->get_download_state();
	$film_torrent_link = $films->get_torrent_link();
	$time_to_go = $films->time_to_go();
	$report = $films->get_report();
	$report_film = $films->report_film();
	$suggest = $films->get_suggest($s_film, $vu);
	$nb_view_film = $films->view();
}
?>