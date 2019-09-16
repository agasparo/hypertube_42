<?php

class Hypertube_connexion {

	public function get_user_data() {

		$res = new response();
		$inf = explode('||', $_SESSION['acces_token_hypertube']);
		if (!isset($inf[1]))
			return ($res->get_response('401'));
		$encryption_key = base64_decode($inf[1]);
		$e = explode('::', base64_decode($inf[0]), 2);
		if (!isset($e[1]))
			return ($res->get_response('401'));
		list($encrypted_data, $iv) = explode('::', base64_decode($inf[0]), 2);
		return (unserialize(openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv)));
	}

}

?>