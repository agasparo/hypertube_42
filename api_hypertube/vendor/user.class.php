<?php

class user {

	private $name;
	private $surname;
	private $lang;
	private $login;

	public function __Construct(array $data) {
		$data = $data['user'];
		$request = new http_request('GET', array("user_login" => $data['user_login'], "type" => "update"));
		$data = json_decode($request->send_request(), true);
		$data = $data['user'];
		$this->login = $data['user_login'];
		$this->lang = $data['lang'];
		$this->surname = $data['nom'];
		$this->name = $data['prenom'];
	}

	public function get_login() {
		return ($this->login);
	}

	public function get_name() {
		$res = new response();
		return ($res->create_reponse('200', $this->name));
	}

	public function get_surname() {
		$res = new response();
		return ($res->create_reponse('200', $this->surname));
	}

	public function get_lang() {
		$res = new response();
		return ($res->create_reponse('200', $this->lang));
	}

	public function get_photo() {
		$res = new response();
		$request = new http_request('GET', array("user_login" => $this->login, "type" => "photo_pro"));
		if (!preg_match("#https://#", json_decode($request->send_request())) && !preg_match("#http://#", json_decode($request->send_request()))) {
			return ($res->create_reponse('200', 'http://192.168.99.100:41062/www/hypertube/photos/'.json_decode($request->send_request())));
		}
		return($res->create_reponse('200', json_decode($request->send_request())));
	}

	public function update_name($new_name) {
		$res = new response();
		$check_value = new verif($new_name);
		if (!$check_value->name_surname())
			return ($res->get_response('400'));
		$request = new http_request('PUT', array("user_login" => $this->login, "type" => "prenom", "new_val" => $new_name));
		$rep = json_decode($res->get_response(json_decode($request->send_request())), true);
		if ($rep['response'] == 200)
			$this->name = $new_name;
		return(json_encode($rep));
	}

	public function update_surname($new_name) {
		$res = new response();
		$check_value = new verif($new_name);
		if (!$check_value->name_surname())
			return ($res->get_response('400'));
		$request = new http_request('PUT', array("user_login" => $this->login, "type" => "nom", "new_val" => $new_name));
		$rep = json_decode($res->get_response(json_decode($request->send_request())), true);
		if ($rep['response'] == 200)
			$this->name = $new_name;
		return(json_encode($rep));
	}

	public function update_lang($new_name) {
		$request = new http_request('PUT', array("user_login" => $this->login, "type" => "lang", "new_val" => $new_name));
		$res = new response();
		$rep = json_decode($res->get_response(json_decode($request->send_request())), true);
		if ($rep['response'] == 200)
			$this->name = $new_name;
		return(json_encode($rep));
	}

	public function update_picture($url_picture) {
		$res = new response();
		if (empty($url_picture))
			return ($res->get_response('400'));
		if(!is_array(getimagesize($url_picture)))
			return ($res->get_response('400'));
		$request = new http_request('PUT', array("user_login" => $this->login, "type" => "img", "new_val" => $url_picture));
		$rep = json_decode($res->get_response(json_decode($request->send_request())), true);
		if ($rep['response'] == 200)
			$this->name = $url_picture;
		return(json_encode($rep));
	}

}

?>