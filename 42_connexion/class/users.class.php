<?php

namespace Oauth_42;

Class users {

	/**
	 * @userinfos Array
	 */
	private $userinfos = [];

	public function __Construct() {

		$this->userinfos = $this->get_userinfos();
	}

	private function get_userinfos() {

		$headers = [

			'Authorization: Bearer '.$_SESSION['token_42']
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://api.intra.42.fr/v2/me");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);

		return (json_decode($result, true));
	}

	public function get_user_login() : String {

		return ($this->userinfos['login']);
	}

	public function get_user_name() : String {

		return ($this->userinfos['last_name']);
	}

	public function get_user_surname() : String {

		return ($this->userinfos['first_name']);
	}

	public function get_user_mail() : String {

		return ($this->userinfos['email']);
	}

	public function get_user_lang() : String {

		return ($this->userinfos['campus'][0]['language']['identifier']);
	}

	public function get_user_img() : String {

		return ($this->userinfos['image_url']);
	}
}

?>