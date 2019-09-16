<?php

namespace Oauth_42;

class http_request {

	/**
	 * @args Array
	 */
	private $args = [];

	/**
	 * @url String
	 */
	private $url;

	public function __Construct(Array $param, String $url) {

		$this->args = $param;
		$this->url = $url;
	}

	public function send_request() {

		$fields_string = http_build_query($this->args);

		$ch = curl_init($this->url);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);
		return ($result);
	}
}

?>