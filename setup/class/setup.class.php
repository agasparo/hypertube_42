<?php

namespace Bdd_manager;

Class Setup {

	public function __Construct(Object $bdd) {

		if (file_exists('setup/data/hypertube.sql'))
			$this->executeQueryFile('setup/data/hypertube.sql', $bdd);
		else
			$this->executeQueryFile('../setup/data/hypertube.sql', $bdd);
	}

	public function executeQueryFile(String $filesql, Object $bdd) : Int {
		$query = file_get_contents($filesql);
		$array = explode(";\n", $query);
		$b = true;
		for ($i=0; $i < count($array) ; $i++) {
			$str = $array[$i];
			if ($str != '') {
				$str .= ';';
				$b = $bdd->exec($str);  
			}  
		}
		return ($b);
	}
}

?>