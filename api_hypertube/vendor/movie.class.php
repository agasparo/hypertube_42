<?php

class movie {

	public function show_all() {
		$res = new response();
		$request = new http_request('GET', array("type" => "movie", "val" => "all"));
		$tab = json_decode($request->send_request(), true);
		$reps = $tab;
		unset($reps['success']);
		return($res->create_reponse($tab['success'], $reps));
	}

	public function add($data) {
		$res = new response();
		if (!is_array($data))
			return ($res->get_response('400'));
		$tab_ref = ['film', 'torrent_url', 'name', 'picture', 'resumer', 'note', 'nb_vote', 'auteur', 'time', 'production date', 'casting'];
		foreach ($data as $key => $value) {
			if ($i = array_search($key, $tab_ref))
				unset($tab_ref[$i]);
		}
		if (count($tab_ref) == 1 && $tab_ref[0] == 'film') {
			$tab['type'] = "movie";
			$tab['val'] = "add";
			$tab['film'] = $data;
			$request = new http_request('POST', $tab);
			return ($res->get_response(json_decode($request->send_request())));
		} else
			return ($res->get_response('400'));
	}

	public function delete($id_movie, $userinfos) {
		$res = new response();
		$request = new http_request('DELETE', array("type" => "movie", "id_del" => $id_movie, "id_do" => $userinfos->get_login()));
		return ($res->get_response(json_decode($request->send_request())));
	}
}

?>