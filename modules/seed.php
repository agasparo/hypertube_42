<?php
set_time_limit(0);

if (!isset($bdd))
	require 'bdd.php';

$sources = ["https://www6.cpasbien9.net/categorie/film-streaming?page=9"];
$tab_val = ["TRUEFRENCH", "HDCAM", "FRENCH", "FANSUB", "BDRip", "English", "ENG", "720p", "HDRip", "WEBRip", "1080p", "DVDRIP", "hd", "ts", "xvid", "vo", "en", "streaming", "tc", "dvdscr", "md", "HDlight", "(Octalogie)", "WEBRIP", "Hindi", "MD", "CAM", "HDRIP", "HDTS", "HDCAM-Rip", "HDTC", "XviD", "TS", "WEB-DL", "VOSTFR", "AC3-EVO", "Movies", "HQ", "HD-TC", "BDRiP"];
$table_fini = [];

foreach ($sources as $key => $value) {
	$table_fini[] = get_url_movies(file_get_contents($value), $tab_val);
}
insert_in_bdd($table_fini[0], $bdd);
header("Location:../index.php");


function insert_in_bdd($table, $bdd) {

	foreach ($table as $value) {
		
		foreach ($value as $key => $values) {

			if (isset($values['link_img']) && isset($values['link_torrent']) && isset($values['name'])) {

				$img = $values['link_img'];
				$name = $values['name'];
				$link_torrent = $values['link_torrent'];
				$date_temps = $values['infos'][0];
				$auteur = $values['infos'][1];
				$casting = $values['infos'][2];
				$resumer = $values['infos'][3];
				$note = $values['infos'][6];
				$nb_vote = $values['infos'][7];

				$e = explode("(", $date_temps);
				if (!isset($e[1])) {
					$e[1] = "0";
				} else {
					$e[1] = str_replace('h', '', $e[1]);
					$e[1] = str_replace('min', '', $e[1]);
					$e[1] = str_replace(')', '', $e[1]);
					$conv = explode(" ", $e[1]);
					$conv[0] = intval($conv[0]) * 60;
					$e[1] = $conv[0] + intval($conv[1]);
				}

				$exist = $bdd->prepare("SELECT * FROM films WHERE torrent_url = ?");
				$exist->execute([$link_torrent]);
				if ($exist->rowCount() == 0)
					insert([$name, $link_torrent, $img, $resumer, $note, $nb_vote, $auteur, $e[1], $e[0], $casting, 0], $bdd);
			}
		}
	}
}

function insert($tab, $bdd) {

	$req_film_insert = $bdd->prepare("INSERT INTO films(name, torrent_url, img, resumer, note, nb_vote, auteur, temps_films, date_prod, casting, download) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$req_film_insert->execute($tab);
}

function get_url_movies($url, $tab_val) {

	$tab = [];

	preg_match_all("/<a\s[^>]*href=([\"\']??)([^\" >]*?)\\1[^>]* class=\"titre\">(.*)<\/a>/siU", $url, $matches);
	$tab[] = get_infos_cpasbien($matches[2], $tab_val);
	return($tab);
}

function get_infos_cpasbien($table, $tab_val) {

	$tab = [];

	foreach ($table as $value) {
	
		$html = file_get_contents($value);

		preg_match_all('/<img src="(.*)" alt="(.+)">/', $html, $picture);
		$tab[$value]['link_img'] = $picture[1][0];

		if (url_exists($tab[$value]['link_img'])) {

			preg_match_all('#<a href="'.$value.'" title="(.+)">(.+)</a>#', $html, $title);
			$tab[$value]['name'] = epur_name($title[2][0], $tab_val);

			preg_match_all('#<a href="https://cestpasbien.pro/torrents/(.+).torrent">(.*)</a>#', $html, $link_torrent);
			if (isset($link_torrent[1][0]))
				$tab[$value]['link_torrent'] = "https://cestpasbien.pro/torrents/".$link_torrent[1][0].".torrent";
			$r = get_infos_on_allocine("http://www.allocine.fr/recherche/?q=".str_replace(" ", "+", $tab[$value]['name']));
			if ($r == 0)
				unset($tab[$value]);
			else
				$tab[$value]['infos'] = $r;
		}
	}

	return ($tab);
}

function get_infos_on_allocine($url) {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$page = curl_exec($ch);
	curl_close($ch);
	preg_match_all('#<a href=(.*)#', $page, $matche);
	$i = 1;
	while (isset($matche[1][$i])) {
		if (preg_match("#/film/fichefilm_gen_cfilm=#", $matche[1][$i])) {
			$ed = explode("'", $matche[1][$i]);
			break;
		}
		$i++;
	}
	if (!isset($ed[1]))
		return (0);
	$data = file_get_contents("http://www.allocine.fr".$ed[1]);
	$dat = explode('<', $data);
	foreach ($dat as $key => $value) {
		if (preg_match('#div class="meta-body">#', $value)) {
			break;
		}
	}
	$dat = explode($value, $data);
	$dat = explode("<div", $dat[1]);
	$i = 1;
	$tab = [];
	$a = 6;
	$b = 16;
	while ($i < $a) {
		$dat[$i] = preg_replace("# +#", " ", $dat[$i]);
		$dat[$i] = str_replace("\n", "", $dat[$i]);
		$dat[$i] = trim(strip_tags($dat[$i]));
		$dat[$i] = str_replace(" plus", "", $dat[$i]);
		$dat[$i] = str_replace("Avec", "", $dat[$i]);
		$dat[$i] = str_replace("De", "", $dat[$i]);
		$dat[$i] = str_replace("en DVD", "", $dat[$i]);
		$dat[$i] = str_replace(" Date de sortie", "", $dat[$i]);
		if (!preg_match("#Date de reprise#", $dat[$i])) {
			array_push($tab, str_replace('class="meta-body-item">', '', trim($dat[$i])));
		} else {
			$b = 17;
			$a = 7;
		}
		$i++;
	}
	$i = 0;
	while (isset($dat[$i])) {
		if (preg_match('#class="content-txt ">#', $dat[$i])) {
			$dat[$i] = preg_replace("# +#", " ", str_replace("\n", "", trim(strip_tags($dat[$i]))));
			$tab[3] = trim(str_replace('class="content-txt ">', "", $dat[$i]));
			break;
		}
		$i++;
	}
	if (preg_match("#Genre#", $tab[3]))
		$tab[3] = "pas de resumer disponible";
	$e = explode("Bande-annonce", $tab[4]);
	$tab[4] = $e[0];
	$tab[6] = intval(str_replace("\n", "", str_replace('class="star icon">', '',preg_replace("# +#", " ",trim(strip_tags($dat[$b])))))) * 2;
	$tab[7] = rand(5, 200);
	return ($tab);
}

function url_exists($url_a_tester) {

	$F = @fopen($url_a_tester,"r");

	if($F) {
		fclose($F);
		return true;
	}
	else return false;
}

function epur_name($str, $tab_val) {

	$e = explode(" ", $str);
	$i = 0;
	$a = 0;
	while (isset($e[$i])) {
		if ($a == 1)
			unset($e[$i]);
		if (isset($e[$i]) && is_numeric($e[$i]) && strlen($e[$i]) == 4) {
			unset($e[$i]);
			$a = 1;
		}
		if (isset($e[$i]) && in_array(strtoupper($e[$i]), $tab_val))
			unset($e[$i]);
		if (isset($e[$i]) && in_array($e[$i], $tab_val))
			unset($e[$i]);
		$i++;
	}
	return (implode(" ", $e));
}
?>