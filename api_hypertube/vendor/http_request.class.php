<?php

class http_request {

	private $methode;
	private $args = [];

	public function __Construct($methode, array $param) {
		$this->methode = $methode;
		$this->args = $param;
	}

	public function send_request() {

		if ($this->methode != 'GET') {

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

			$result = file_get_contents('http://192.168.99.100.xip.io:41062/www/hypertube/api/api.php', false, $context);
		} else {
			$parametres = "";
			foreach ($this->args as $key => $value) {
				$parametres .= $key.'='.urlencode($value)."&";	
			}
			$parametres = substr($parametres, 0,  -1);
			$result = file_get_contents('http://192.168.99.100.xip.io:41062/www/hypertube/api/api.php?'.$parametres);
		}
		return ($result);
	}
}

?>