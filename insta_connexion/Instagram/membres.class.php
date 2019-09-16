<?php

namespace Instagram;

Class membres {

	private $stats;
	private $lang;
	private $login;

	public function __Construct($login, $bdd) {
		$this->login = $login;
		$req_bdd = $bdd->prepare('SELECT * FROM membre WHERE user_login = ? AND api = ?');
		$req_bdd->execute(array(str_replace(" ", "_", $login), 1));
		$infs = $req_bdd->fetch();
		if ($req_bdd->rowCount() == 0)
			$this->stats = 0;
		else {
			$this->lang = $infs['lang'];
			$this->stats = $infs['id'];
		}
	}

	public function exec_member($pic, $bdd) {
		if ($this->stats == 0) {
			$this->lang = "en_US";
			$sur_n = explode(" ", $this->login);
			$this->login = str_replace(" ", "_", $this->login);
			$insert_user = $bdd->prepare('INSERT INTO membre(user_login, prenom, nom, mail, password, lang, api) VALUES(?, ?, ?, ?, ?, ?, ?)');
			$insert_user->execute(array($this->login, $sur_n[0], $sur_n[1], "", $this->create_pass(rand(23, 46)), "en_US", 1));
			$new_val = $bdd->prepare('SELECT * FROM membre WHERE user_login = ? AND api = ?');
			$new_val->execute(array($this->login, 1));
			$infs = $new_val->fetch();
			$inser_photo = $bdd->prepare('INSERT INTO img_users(id_user, img) VALUES(?, ?)');
			$inser_photo->execute(array($infs['id'], $pic));
			return ($infs['id']);
		} else
			return ($this->stats);
	}

	public function get_user_lang() {
		return ($this->lang);
	}

	public function create_pass($length) {

		require '../class/crypt.class.php';

		$e = new \crypt();

		$caracteres = 'abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$longueurMax = strlen($caracteres);
		$chaineAleatoire = '';
		$i = 0;
		while ($i < $length) {
			$chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
			$i++;
		}
		return ($e->encrypt($chaineAleatoire));
	}
}

?>