<?php

if (!file_exists('panel/vendor/autoload.php'))
	require '../panel/vendor/autoload.php';
else
	require 'panel/vendor/autoload.php';
use Stichoza\GoogleTranslate\TranslateClient;

Class traduction {

	/**
	 * @all_vars Array
	 */
	private $all_vars = [];

	/**
	 * @lang String
	 */
	private $lang;

	/**
	 * @p String
	 */
	private $p;

	public function __Construct(array $vars, String $lang, String $path) {
		$this->all_vars = $vars;
		$lang = explode("_", $lang);
		$this->lang = $lang[0];
		$this->p = $path;
		$this->suppr_vars();
	}

	public function suppr_vars() {

		$tab_key = ['bdd', 'list_mov', 'DB_USER', 'DB_PASSWORD', 'id_page', 'DB_DSN', 'mov', 'img', 'torrent'];

		foreach ($this->all_vars as $key => $value) {

			if (is_array($value) || in_array($key, $tab_key) || $key == 'path' || $key == 'file' || $key == "last_visit" || $key == "last")
				unset($this->all_vars[$key]);
			else if (preg_match("#link#", $key)) 
				unset($this->all_vars[$key]);
			else if (is_numeric($value))
				unset($this->all_vars[$key]);
		}
	}

	public function set_trad() : Array {

		if (!file_exists($this->p.'panel/trad/'.$this->lang)) {
			mkdir($this->p.'panel/trad/'.$this->lang);
			mkdir($this->p.'panel/trad/'.$this->lang.'/film');
			mkdir($this->p.'panel/trad/'.$this->lang.'/all');
			mkdir($this->p.'panel/trad/'.$this->lang.'/use');
		}

		if (!file_exists($this->p.'panel/trad/'.$this->lang.'/all/'.$this->lang.'.txt')) {
			$fd = fopen($this->p.'panel/trad/'.$this->lang.'/all/'.$this->lang.'.txt', 'w+');
			fclose($fd);
		}

		$data = unserialize(file_get_contents($this->p.'panel/trad/'.$this->lang.'/all/'.$this->lang.'.txt'));

		if (empty($data))
			$data = [];

		if (count($data) != count($this->all_vars)) {
			$tr = new TranslateClient();
			$tr->setSource('fr');
			$tr->setTarget($this->lang);
			if (preg_match("#Client error:#", $a = $tr->translate("voiture")))
				return ([]);
			foreach ($this->all_vars as $key => $value) {
				$this->all_vars[$key] = $tr->translate($value);
				while (preg_match("#Client error:#", $this->all_vars[$key])) {
					$this->all_vars[$key] = $tr->translate($value);
				}

			}
			file_put_contents($this->p.'panel/trad/'.$this->lang.'/all/'.$this->lang.'.txt', serialize($this->all_vars));
			return ($this->all_vars);
		}
		return ($data);
		
	}

	public function set_trad_films(String $identifiant) : Array {

		if (!file_exists($this->p.'panel/trad/'.$this->lang.'/film/'.$identifiant.'.txt')) {
			$fd = fopen($this->p.'panel/trad/'.$this->lang.'/film/'.$identifiant.'.txt', 'w+');
			fclose($fd);
		}

		$data = unserialize(file_get_contents($this->p.'panel/trad/'.$this->lang.'/film/'.$identifiant.'.txt'));

		if (empty($data))
			$data = [];

		if (count($data) != count($this->all_vars)) {
			$tr = new TranslateClient();
			$tr->setSource('fr');
			$tr->setTarget($this->lang);
			if (preg_match("#Client error:#", $a = $tr->translate("voiture")))
				return ([]);

			foreach ($this->all_vars as $key => $value) {
				$this->all_vars[$key] = $tr->translate($value);
				while (preg_match("#Client error:#", $this->all_vars[$key])) {
					$this->all_vars[$key] = $tr->translate($value);
				}
			}
			file_put_contents($this->p.'panel/trad/'.$this->lang.'/film/'.$identifiant.'.txt', serialize($this->all_vars));
			return ($this->all_vars);
		}
		return ($data);
	}

	public function set_trad_recp(String $identifiant, Array $datas) : Array {
		if (!file_exists($this->p.'panel/trad/'.$this->lang.'/use/'.$identifiant.'.txt')) {
			$fd = fopen($this->p.'panel/trad/'.$this->lang.'/use/'.$identifiant.'.txt', 'w+');
			fclose($fd);
		}

		$data = unserialize(file_get_contents($this->p.'panel/trad/'.$this->lang.'/use/'.$identifiant.'.txt'));

		if (empty($data))
			$data = [];

		if (count($data) != count($datas)) {
			$tr = new TranslateClient();
			$tr->setSource('fr');
			$l = explode('_', $this->lang);
			$tr->setTarget($l[0]);
			if (preg_match("#Client error:#", $a = $tr->translate("voiture")))
				return ([]);
			foreach ($datas as $key => $value) {
				$datas[$key] = $tr->translate($value);
				while (preg_match("#Client error:#", $datas[$key])) {
					$datas[$key] = $tr->translate($value);
				}
			}
			file_put_contents($this->p.'panel/trad/'.$this->lang.'/use/'.$identifiant.'.txt', serialize($datas));
			return ($datas);
		}
		return ($data);
	}

}

?>