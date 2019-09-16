<?php

class jeux {

	public function list_jeux() : String {
		$tabs = json_decode(file_get_contents('http://192.168.99.100:41062/www/matcha_42/api/index.php?type=jeux'), true);
		$str = "";
		foreach ($tabs['infos']['game'] as $key => $value) {
			$str .= 'game : '.$key.' --> '.$value."<br>";
		}
		return ($str);
	}
}