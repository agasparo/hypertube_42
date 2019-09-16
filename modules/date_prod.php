<?php

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {

	$execp = ['Prochainement'];
	$show = [];
	$or = $_POST['type'];
	$final_array = [];
	$mois = ["janvier" => 1, "février" => 2, "mars" => 3, "avril" => 4, "mai" => 5, "juin" => 6, "juillet" => 7, "août" => 8, "septembre" => 9, "octobre" => 10, "novembre" => 11, "décembre" => 12];

	$get_all = $bdd->query("SELECT * FROM films");

	$i = 0;

	while ($data_date = $get_all->fetch()) {

		$show[$data_date['id']] = $data_date;

		$to_date = explode(" ", trim($data_date['date_prod']));
		if (count($to_date) > 3)
			$to_date = array_slice($to_date, 0, 3);
		if (!isset($to_date[2]) || !array_key_exists($to_date[1], $mois))
			$final_array[$data_date['id']] = time();
		else if (checkdate($mois[$to_date[1]], $to_date[0], $to_date[2]))
			$final_array[$data_date['id']] = strtotime($to_date[0]."-".$mois[$to_date[1]]."-".$to_date[2]);
		else if (!in_array($data_date['id'], $final_array))
			$final_array[$data_date['id']] = time();
	}

	if ($or == 1)
		arsort($final_array);
	else
		asort($final_array);


	foreach ($final_array as $key => $value) {
		
		$final_array[$key] = $show[$key];
	}

	$_SESSION['all'] = array_values($final_array);
}

?>