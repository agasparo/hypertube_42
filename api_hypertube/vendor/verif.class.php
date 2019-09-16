<?php

class verif {

	private $value;

	public function __Construct($val) {
		$this->value = $val;
	}

	public function is_ok() {
		if (is_array($this->value))
			return (1);
		$data = json_decode($this->value, true);
		if (isset($data['response']))
			return (0);
		return (1);
	}

	public function show_ch() {
		return ($this->value);
	}

	public function name_surname() {
		if (strlen($this->value) < 3)
			return (0);
		return (1);
	}

}

?>