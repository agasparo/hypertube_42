<?php
if (isset($_SESSION['lang']) && !empty($_SESSION['lang'])) {
	$l = explode("_", $_SESSION['lang']);
	if ($l[0] != 'fr') {
		$trads = new traduction(array("film_prod" => $film_prod, "film_resu" => $film_resu), $_SESSION['lang'], '');
		$arr = $trads->set_trad_films($id);
		if (is_array($arr))
			extract($arr);
	}
}

?>