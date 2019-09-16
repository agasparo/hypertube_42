<?php

class recomp {

	/**
	 * @all_rec Array
	 */
	private $all_rec = [];

	/**
	 * @all_rec_int Array
	 */
	private $all_rec_int = [];

	public function __Construct() {
		$data = file('data/rec.txt');
		$l = explode("_", $_SESSION['lang']);
		if ($l[0] != 'fr') {
			$trad = new traduction($data, $_SESSION['lang'], '');
			$arr = $trad->set_trad_recp($l[0]."_rec", $data);
			if (is_array($arr))
				$data = $arr;
		}

		if (empty($data))
			$data = file('data/rec.txt');

		foreach ($data as $value) {

			$this->all_rec[] = $value;
			$this->all_rec_int[] = $this->tab_set($value);
		}
	}

	public function get(Array $tab, Int $id_user) : String {
		require 'modules/bdd.php';
		$get_req = $bdd->prepare("SELECT * FROM recomp WHERE id_user = ?");
		$get_req->execute(array($id_user));
		$b = "<tr><th scope='col'>".$tab[1]."</th><th scope='col'>".$tab[0]."</th></tr>";
		while ($recompense = $get_req->fetch()) {
			$b .= "<tr><td>".$this->all_rec[$recompense["id_recomp"]]."</td><td>".$recompense["date_ob"]."</td></tr>";
		}
		return ($b);
	}

	public function set() {
		require 'modules/bdd.php';
		$tab_tb = ['vu', 'vu_movies'];
		$i = 0;
		while (isset($tab_tb[$i])) {

			$check = $bdd->prepare("SELECT * FROM ".$tab_tb[$i]." WHERE id_user = ?");
			$check->execute(array($_SESSION['id']));

			if ($check->rowCount() >= $this->all_rec_int[$i]) {
				$check_is = $bdd->prepare("SELECT * FROM recomp WHERE id_user = ? AND id_recomp = ?");
				$check_is->execute(array($_SESSION['id'], $i));
				if ($check_is->rowCount() == 0) {
					$insert = $bdd->prepare("INSERT INTO recomp(id_user, id_recomp, date_ob) VALUES(?, ?, now())");
					$insert->execute(array($_SESSION['id'], $i));
				}
			}
			$i++;
		}
	}

	public function tab_set(String $val) : Int {
		$e = explode(" ", $val);
		foreach ($e as $value) {
			if (is_numeric($value))
				return ($value);
		}
	}
}
?>