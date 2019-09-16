<?php

class controler {
	
	public function show_film($id) {
		if (!is_numeric($id) || $id <= 0)
			header("Location:http://192.168.99.100.xip.io:41062/www/hypertube/");
		else {
			if (!isset($_SESSION))
				session_start();
			$_SESSION['id_film'] = $id;
			require 'panel/control.panel.php';
			require 'modules/bdd.php';
			require 'class/stream.class.php';
			require 'class/films.class.php';
			require 'modules/infos_films.php';
			require 'template/films.php';
		}
	}

	public function not_found() {
		echo $this->replace_404();
	}

	private function replace_404() {
		require 'panel/control.panel.php';
		$data = file_get_contents('template/404.html');
		preg_match_all("#{{(.+)}}#", $data, $matches);
		$i = 0;
		while (isset($matches[0][$i])) {
			$data = str_replace($matches[0][$i], ${$matches[1][$i]}, $data);
			$i++;
		}
		return ($data);
	}

	public function show_homepage() {
		$id_page = func_get_args();
		if (empty($id_page))
			$id_page = 1;
		else
			$id_page = intval($id_page[0]);

		if ($id_page < 0)
			$id_page = 1;

		require 'modules/bdd.php';

		if (isset($db_error))
			$this->show_setup();
		else {
			require 'modules/old_movie.php';
			require 'panel/control.panel.php';
			require 'template/home.php';
		}
	}

	public function show_seed() {
		require 'modules/bdd.php';
		require 'modules/seed.php';
	}

	public function show_user($id_user) {
		if (empty($id_user))
			$id_user = 1;
		else
			$id_user = intval($id_user);
		require 'panel/control.panel.php';
		require 'modules/bdd.php';
		require 'template/user.php';
	}

	public function show_setup() {
		require 'modules/bdd.php';
		require 'setup/index.php';
	}

}

?>