<?php

class http_request {

	/**
	 * @methode String
	 */
	private $methode;

	/**
	 * @args Array
	 */
	private $args = [];

	public function __Construct(String $methode, Array $param) {
		$this->methode = $methode;
		$this->args = $param;
	}

	public function send_request(String $url) : String {
		
		$postdata = http_build_query(
			$this->args
		);

		$opts = array('http' =>
			array(
				'method'  => $this->methode,
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);

		$context  = stream_context_create($opts);

		return(file_get_contents($url, false, $context));
	}
}

?>