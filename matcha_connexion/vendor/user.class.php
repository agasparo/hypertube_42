<?php

class user {

	public function connect_user() {
		$token = $_SESSION['acces_token_matcha'];
		$pass = $_SESSION['pass_token'];
		$get_token = openssl_decrypt($token, 'BF-CFB', $pass);
		$tabs = unserialize($get_token);
		$tab['success'] = 1;
		$tab['message'] = "data get connection ok";
		$tab['infos'] = $tabs;
		return (json_encode($tab));
	}

	public function get_user_musique($a) {
		return (file_get_contents('http://192.168.99.100:41062/www/matcha_42/api/index.php?type=musique_user&tab='.urlencode(serialize($a))));
	}

	public function musique_play(Array $tab) {
		$tab = json_decode($tab, true);
		if (!is_array($tab['infos']))
			return ($tab['infos']);
		$i = 1;
		$str = '';
		while (isset($tab['infos'][$i])) {
			$e = explode('||', $tab['infos'][$i]);
			$str .= 'id musique : '.$e[0].' number of heard : '.$e[1]."<br>";
			$i++;
		}
		return ($str);
	}

	public function get_user_followers($a) {
		return (file_get_contents('http://192.168.99.100:41062/www/matcha_42/api/index.php?type=follow&tab='.urlencode(serialize($a))));
	}

	public function followers_play($tab) {
		$tab = json_decode($tab, true);
		if (isset($tab['infos']['nb_followers']))
			return ($tab['infos']['nb_followers']);
		else
			return ($tab['infos']);
	}

	public function get_user_localisation($a) {
		return (file_get_contents('http://192.168.99.100:41062/www/matcha_42/api/index.php?type=local&tab='.urlencode(serialize($a))));
	}

	public function get_user_game($a) {
		return (file_get_contents('http://192.168.99.100:41062/www/matcha_42/api/index.php?type=game_vid&tab='.urlencode(serialize($a))));
	}

	public function game_play($tab) {
		$tab = json_decode($tab, true);
		if (isset($tab['infos']['game']))
			return ($tab['infos']['game'].' --> '.$tab['infos']['time_played']);
		else
			return ($tab['infos']);
	}

	public function local_city($tab) {
		$tab = json_decode($tab, true);
		return ($tab['infos']['city']);
	}

	public function local_region($tab) {
		$tab = json_decode($tab, true);
		return ($tab['infos']['region']);
	}

	public function local_pays($tab) {
		$tab = json_decode($tab, true);
		return ($tab['infos']['pays']);
	}

	public function get_user_prenom($tab) {
		$tabs = json_decode($tab, true);
		return ($tabs['infos']['user']['prenom']);
	}

	public function get_user_nom($tab) {
		$tabs = json_decode($tab, true);
		return ($tabs['infos']['user']['nom']);
	}

	public function get_user_mail($tab) {
		$tabs = json_decode($tab, true);
		return ($tabs['infos']['user']['mail']);
	}

	public function get_user_sexe($tab) {
		$tabs = json_decode($tab, true);
		if ($tabs['infos']['user']['sexe'] == "M")
			return ("Homme");
		if ($tabs['infos']['user']['sexe'] == "F")
			return ("Femme");
		return ('Autre');
	}

	public function get_user_attirance($tab) {
		$tabs = json_decode($tab, true);
		if ($tabs['infos']['user']['attirance'] == "M")
			return ("Homme");
		if ($tabs['infos']['user']['attirance'] == "F")
			return ("Femme");
		return ('Autre');
	}

	public function get_user_description($tab) {
		$tabs = json_decode($tab, true);
		return ($tabs['infos']['user']['description']);
	}

	public function get_user_photos($tab) {
		$tabs = json_decode($tab, true);
		if (preg_match("#https://#", $tabs['infos']['user']['photos']) || preg_match("#http://#", $tabs['infos']['user']['photos']))
			return ($tabs['infos']['user']['photos']);
		return ('http://192.168.99.100:41062/www/matcha_42/photos/'.$tabs['infos']['user']['photos']);
	}

	public function get_user_photos_pro($tab) {
		$tabs = json_decode($tab, true);
		if (preg_match("#https://#", $tabs['infos']['user']['photos_pro']) || preg_match("#http://#", $tabs['infos']['user']['photos_pro']))
			return ($tabs['infos']['user']['photos_pro']);
		return ('http://192.168.99.100:41062/www/matcha_42/photos/'.$tabs['infos']['user']['photos_pro']);
	}

	public function get_user_age($tab) {
		$tabs = json_decode($tab, true);
		return ($tabs['infos']['user']['age']);
	}

	public function get_user_interets($tab) {
		$tabs = json_decode($tab, true);
		return ($tabs['infos']['user']['interets']);
	}
}