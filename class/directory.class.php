<?php

Class directory_check {


	/**
	 * @files Array
	 */
	private $files = [];

	/**
	 * @dir Array
	 */
	private $dir = [];

	public function __Construct() {

		$this->files = glob("*");
		foreach ($this->files as $value) {
			if (is_dir($value))
				$this->dir[] = $value;
			else
				$this->files[] = $value;
		}
	}

	public function check() : Int {

		if ($_GET['url'][strlen($_GET['url']) - 1] == "/")
			$_GET['url'] = substr($_GET['url'], 0, -1);
		$url = explode("/", $_GET['url']);
		if (!in_array($url[count($url) - 1], $this->dir))
			return (1);
		return (0);
	}

}

?>