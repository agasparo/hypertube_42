<?php

require 'musique.class.php';
require 'user.class.php';
require 'jeux.class.php';

class Match_connection {

	public function ini_user() : Object {
		return ($users = new user());
	}

	public function init_musique() : Object {
		return ($musiques = new musique());
	}

	public function init_jeux() : Object {
		return ($jeux = new jeux());
	}
}

?>