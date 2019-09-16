<?php

class trie {


	/**
	 * @bdd Object
	 */
	private $bdd;

	/**
	 * @req String
	 */
	private $req;

	/**
	 * @res Array
	 */
	private $res = [];

	/**
	 * @final Array
	 */
	private $final = [];

	/**
	 * @min Int
	 */
	private $min;

	/**
	 * @max Int
	 */
	private $max;

	public function __Construct($bdd, String $req, String $order, Int $depart, Int $fims_par_page,  Int $t) {
		$this->bdd = $bdd;
		$this->req = 'SELECT * FROM films '.$req.$order;
		if ($t == 1) {
			$this->res = $this->bdd->query($this->req);
			$this->res = $this->res->fetchAll();
		}
		$this->min = $depart;
		$this->max = $fims_par_page + $depart;
	}

	public function result() : Array {
		while ($this->min < $this->max && isset($this->res[$this->min])) {
			$this->final[$this->min] = $this->res[$this->min];
			$this->min++;
		}
		return ($this->final);
	}

	public function get_tab() : Array {
		return ($this->res);
	}

	public function with_tab(Array $tab, Int $min, Int $max) : Array {
		while ($min < $max && isset($tab[$min])) {
			$this->final[] = $tab[$min];
			$min++;
		}
		return ($this->final);
	}
}

?>