<?php

Class init_film {

	/**
	 * @data String
	 */
	private $data;

	/**
	 * @link String
	 */
	private $link;

	public function __Construct(String $path, Int $buff, Int $id) {

		$this->link = "../tmp/".$id.'_'.uniqid().".php";
		$this->data = '<head><meta charset="utf-8"></head>'."\n".file_get_contents('../template/stream_create.html');
		$this->data = str_replace("{{path}}", $path, $this->data);
		$this->data = str_replace("{{buff}}", $buff, $this->data);
		$this->write();
		$this->redirect();
	}

	public function write() {
		file_put_contents($this->link, $this->data);
	}

	public function redirect() {
		header("Location:".$this->link);
	}
}

?>