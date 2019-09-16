<?php

class autoload {

	public function __Construct() {
		require 'vendor/http_request.class.php';
		require 'vendor/response.class.php';
		require 'vendor/verif.class.php';
		require 'vendor/hypertube.class.php';
		require 'vendor/user.class.php';
		require 'vendor/movie.class.php';
	}

	public function help() {
		return ('<h1>Documentation :</h1><br><iframe src="vendor/documentation/documentation.html" width="100%" height="70%"></iframe>');
	}
}

$auto = new autoload();
?>