<?php

namespace Oauth_42;

Class Connexion {

	const UID = "";
	const SECRET = "";
	const REDIRECT_URL = 'http://192.168.99.100.xip.io:41062/www/hypertube/42_connexion/index.php';

	/**
	 * @token String
	 */
	private $token;

	/**
	 * @code String
	 */
	private $code = "none";

	public function __Construct() {

		if (!isset($_SESSION['token_42']) || empty($_SESSION['token_42'])) {
			
			if (isset($_GET['code']) && !empty($_GET['code']))
				$this->code = $_GET['code'];
			else
				$this->show();

		} else
			$this->token = $_SESSION['token_42'];

		if ($this->code != "none") {

			$this->token = $this->get_token();
			$_SESSION['token_42'] = $this->token;
		}

	}

	private function show() {

		echo "<a href='https://api.intra.42.fr/oauth/authorize?client_id=".self::UID."&redirect_uri=http%3A%2F%2F192.168.99.100.xip.io%3A41062%2Fwww%2Fhypertube%2F42_connexion%2Findex.php&response_type=code'><img src='photos/42_api.png' style='width:10vw;'></a>";
	}

	private function get_token() : String {

		$data = [

			"grant_type" => 'authorization_code',
			"client_id" => self::UID,
			"client_secret" => self::SECRET,
			"code" => $this->code,
			"redirect_uri" => self::REDIRECT_URL,
		];
		
		$request_http = new http_request($data, "https://api.intra.42.fr/oauth/token");
		$response = $request_http->send_request();

		$response = json_decode($response, true);

		return ($response['access_token']);
	}

	public function success() : Int {

		if (isset($this->token) && !empty($this->token))
			return (1);
		return (0);
	}
}

?>