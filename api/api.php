<?php

require '../modules/bdd.php';

$tab_lang = ["Fr", "En"];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (isset($_GET['type']))
		$type = $_GET['type'];
	else
		$type = "";
	if ($type == "movie") {
		if ($_GET['val'] == "all") {
			echo json_encode(req_all($bdd, 'films'));
		}
	} else {
		if (isset($_GET['user_login']))
			$login = $_GET['user_login'];
		else
			$login = "";
		if ($type == "photo_pro") {
			$id = req_get($bdd, 'membre', 'user_login', $login, 'id');
			echo json_encode(req_get($bdd, 'img_users', 'id_user', $id, 'img'));
		} else if ($type == "update") {
			$te = update($bdd, $login);
			if ($te) {
				echo json_encode($te);
			} else {
				echo json_encode('401');
			}
		}
	}
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
	parse_str(file_get_contents('php://input'), $_PUT);
	if ($_PUT['type'] == 'lang') {
		if (!in_array($_PUT['new_val'], $tab_lang)) {
			echo json_encode('400');
			return (0);
		}
	}
    $id = req_get($bdd, 'membre', 'user_login', $_PUT['user_login'], 'id');
    $modif = req_get($bdd, 'membre', 'user_login', $_PUT['user_login'], 'api_modif');
    if ($modif == 0) {
    	echo json_encode('403');
    	return (0);
    }
    if ($_PUT['type'] == "img") {
    	if(!is_array(getimagesize($_PUT['new_val']))) {
    		echo json_encode('304');
    		return (0);
    	}
    	if (req_put($bdd, 'img_users', $_PUT['type'], 'id', [$_PUT['new_val'], $id])) {
			echo json_encode('200');
    	} else {
			echo json_encode('304');
		}
    } else {
    	if (req_put($bdd, 'membre', $_PUT['type'], 'id', [$_PUT['new_val'], $id])) {
			echo json_encode('200');
    	} else {
			echo json_encode('304');
		}
	}
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_POST["type"] == "movie" && $_POST["val"] == "add") {
		if (req_post_add($bdd, $_POST['film']))
			echo json_encode('201');
		else
			echo json_encode('400');
	}
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
	parse_str(file_get_contents('php://input'), $_DELETE);
	if ($_DELETE['type'] == "movie" && isset($_DELETE['id_del'])) {
		$rep = delete_info($bdd, $_DELETE['id_del'], $_DELETE['id_do']);
		if ($rep == 1)
			echo json_encode('403');
		if ($rep == 2)
			echo json_encode('400');
		if ($rep == 3)
			echo json_encode('202');
	} else {
		echo json_encode('400');
	}
}

function delete_info($bdd, $id_del, $id_do) {
	$has_right = $bdd->prepare("SELECT * FROM right_erase WHERE user_login = ?");
	$has_right->execute(array($id_do));
	if ($has_right->rowCount() > 0) {
		$delete = $bdd->prepare("DELETE FROM films WHERE id = ?");
		$delete->execute(array($id_del));
		if ($delete->rowCount() > 0)
			return (3);
		return (2);
	}
	return (1);
}

function req_post_add($bdd, $tab_value) {
	if (preg_match('/[a-zA-Z]/i', trim($tab_value['time'])))
		return (0);
	if (preg_match('/[a-zA-Z]/i', trim($tab_value['note'])))
		return (0);
	if (preg_match('/[a-zA-Z]/i', trim($tab_value['nb_vote'])))
		return (0);
	if (!preg_match("#.torrent#", trim($tab_value['torrent_url'])))
		return (0);
	if ($tab_value['time'] < 0 || $tab_value['time'] > 300)
		return (0);
	if ($tab_value['note'] < 0 || $tab_value['note'] > 10)
		return (0);
	if ($tab_value['nb_vote'] < 0 || $tab_value['nb_vote'] > 1000000)
		return (0);
	$i = 0;
	$tab_ref = ['name', 'torrent_url', 'picture', 'resumer', 'note', 'nb_vote', 'auteur', 'time', 'production date', 'casting'];
	foreach ($tab_value as $key => $value) {
		if (empty($value) && $key != 'note' && $key != "nb_vote" && $key != "time")
			return (0);
		if ($key != $tab_ref[$i])
			return (0);
		$i++;
	}
	if(!is_array(getimagesize(trim($tab_value['picture']))))
		return (0);
	$check_film = $bdd->prepare("SELECT * FROM films WHERE torrent_url = ?");
	$check_film->execute(array($tab_value['torrent_url']));
	if ($check_film->rowCount() == 0) {
		$req_film_insert = $bdd->prepare("INSERT INTO films(name, torrent_url, img, resumer, note, nb_vote, auteur, temps_films, date_prod, casting) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$req_film_insert->execute(array_values($tab_value));
		if ($req_film_insert->rowCount() > 0)
			return (1);
		return (0);
	}
	return (0);
}

function update($bdd, $login) {
	$req_user = $bdd->prepare('SELECT * FROM membre WHERE user_login = ?');
	$req_user->execute(array($login));
	if ($req_user->rowCount() > 0) {
		$tab = [];
		$userinfos = $req_user->fetch();
		foreach ($userinfos as $key => $value) {
			if (!is_numeric($key) && $key != 'password' && $key != 'id' && $key != 'mail')
				$tab['user'][$key] = $value;
		}
		return ($tab);
	}
	return (0);
}

function req_all($bdd, $table) {
	$req = $bdd->query("SELECT * FROM ".$table);
	$tab_film = [];
	while ($inf = $req->fetch()) {
		$film['id'] = $inf['id'];
		$film['name'] = $inf['name'];
		$film['picture'] = $inf['img'];
		$film['resumer'] = $inf['resumer'];
		$film['note'] = $inf['note'];
		$film['nb_vote'] = $inf['nb_vote'];
		$film['auteur'] = $inf['auteur'];
		$film['time'] = $inf['temps_films'];
		$film['production date'] = $inf['date_prod'];
		$film['casting'] = $inf['casting'];
		array_push($tab_film, $film);
	}
	$tab['success'] = '200';
	$tab['nb_res'] = $req->rowCount();
	$tab['results'] = $tab_film;
	return ($tab);
}

function req_put($bdd, $table, $set_inf, $where_inf, $more) {
	$req = $bdd->prepare("UPDATE ".$table." SET ".$set_inf." = ? WHERE ".$where_inf." = ?");
	$req->execute($more);
	if ($req->rowCount() > 0)
		return (1);
	return (0);
}

function req_get($bdd, $table, $where_inf, $more, $call) {
	$req = $bdd->prepare("SELECT * FROM ".$table." WHERE ".$where_inf." = ?");
	$req->execute(array($more));
	$inf = $req->fetch();
	return ($inf[$call]);
}

?>