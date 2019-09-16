<?php

if (!isset($bdd))
	exit(0);

$list_mov = $bdd->query("SELECT * FROM films");

while ($mov = $list_mov->fetch()) {
	$last_visit = $bdd->prepare("SELECT * FROM vu_movies WHERE id_movie = ? ORDER BY id DESC");
	$last_visit->execute(array($mov['id']));
	$last = $last_visit->fetch();
	if ($last_visit->rowCount() > 0) {
		$last_visit = $last['temps'];
		$mois = 2629800;
		if (time() - $last_visit >= $mois) {
			$delete = $bdd->prepare("DELETE FROM films WHERE id = ?");
			$delete->execute(array($mov['id']));
		}
	}
}

?>