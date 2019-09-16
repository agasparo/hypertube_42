<?php

if (!isset($bdd))
	exit(0);

$if_inf = $bdd->prepare("SELECT * FROM films WHERE id = ?");
$if_inf->execute(array($id));
$infos_f = $if_inf->fetch();

$ajj_tab = [];

foreach ($infos_f as $key => $value) {
	if (empty($value) && !is_numeric($key) && $key != "note" && $key != "nb_vote" && $key != "download")
		array_push($ajj_tab, $key);
}

if (trim($infos_f['date_prod']) == 'Prochainement' || !empty($infos_f['auteur']))
	$ajj_tab = [];

if (!empty($ajj_tab)) {
	$infos_f['name'] = str_replace("&#039;", " ", $infos_f['name']);
	$unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
	$infos_f['name'] = strtr(html_entity_decode($infos_f['name']), $unwanted_array);
	$e = explode(" ", $infos_f['name']);
	foreach ($e as $key => $value) {
		if (is_numeric($value) && strlen($value) == 4)
			unset($e[$key]);
		if (is_numeric(str_replace("-", "", $value)))
			unset($e[$key]);
	}
	if (empty($e))
		$e[0] = $infos_f['name'];
	$url = "http://www.allocine.fr/recherche/?q=".str_replace(' ', '+', implode(" ", $e));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$page = curl_exec($ch);
	curl_close($ch);
	preg_match_all('#<a href=(.*)#', $page, $matche);
	$i = 1;
	while (isset($matche[1])) {
		if (preg_match("#/film/fichefilm_gen_cfilm=#", $matche[1][$i])) {
			$ed = explode("'", $matche[1][$i]);
			break;
		}
		$i++;
	}
	$tab = get_infos_on_allocine("http://www.allocine.fr".$ed[1]);
	$e = explode("(", $tab[0]);
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
	$up_film = $bdd->prepare("UPDATE films SET resumer = ?, note = ?, nb_vote = ?, auteur = ?, temps_films = ?, date_prod = ?, casting = ? WHERE id = ?");
	$up_film->execute(array(trim($tab[3]), $tab[6], $tab[7], trim($tab[1]), $e[1], $e[0], trim($tab[2]), $infos_f['id']));
	header("Location:http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
}

function get_infos_on_allocine($url) {
	$data = file_get_contents($url);
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

?>