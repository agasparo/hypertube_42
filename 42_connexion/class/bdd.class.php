<?php

namespace Oauth_42;

Class bdd_reqs {


	/**
	 * @bdd Object
	 */
	private $bdd;

	/**
	 * @response String
	 */
	private $response;

	public function __Construct(Object $bdd) {

		$this->bdd = $bdd;
	}

	public function insert(Array $values, Int $type) {

		if ($type == 1)
			$req_insert = $this->bdd->prepare('INSERT INTO membre(user_login, prenom, nom, mail, password, lang, api) VALUES(?, ?, ?, ?, ?, ?, ?)');
		else
			$req_insert = $this->bdd->prepare('INSERT INTO img_users(id_user, img) VALUES(?, ?)');
		
		$req_insert->execute($values);
	}

	public function select(Array $values) : Int {

		$check = $this->bdd->prepare('SELECT * FROM membre WHERE user_login = ? AND api = ?');
      	$check->execute($values);
      	$this->response = $check->fetch();
      	return ($check->rowCount());
	}

	public function get_res() : Array {

		return ($this->response);
	}
}

?>