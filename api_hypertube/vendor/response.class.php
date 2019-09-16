<?php

class response {

	private $rep = [];
	private $err = [];

	public function __Construct() {
		$this->err = array(
			"304" => "Data not modified",
			"200" => "Data update success",
			"201" => "Data insert success",
			"202" => "Data erase success",
			"400" => "Bad request",
			"401" => "Unauthorized",
			"403" => "Forbidden access restricted"
		);
		$this->rep['response'] = "";
		$this->rep['infos'] = "";
	}

	public function get_response($rep) {

		if (empty($rep))
			$rep = 400;
		
		$this->rep['response'] = $rep;
		$this->rep['infos'] = $this->err[$rep];
		return (json_encode($this->rep));
	}

	public function create_reponse($rep, $val) {
		$this->rep['response'] = $rep;
		$this->rep['infos'] = $val;
		return (json_encode($this->rep));
	}

}

?>