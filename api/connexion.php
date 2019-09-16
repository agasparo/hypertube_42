<?php

require_once '../panel/control.panel.php';
require_once '../modules/bdd.php';
require_once '../class/crypt.class.php';

function my_encrypt($data, $key) {
    $encryption_key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return (base64_encode($encrypted . '::' . $iv));
}

if (isset($_POST['sub_api'])) {
	$mot_de_passe = $_POST['pass_api'];
	$login = htmlspecialchars($_POST['util_api']);
	if (!empty($mot_de_passe) && !empty($login)) {
		
		$req_user = $bdd->prepare('SELECT * FROM membre WHERE user_login = ?');
		$req_user->execute(array($login));

		if ($req_user->rowCount() > 0) {

			$user_log = $req_user->fetch();

			$e = new crypt();
			if($e->compare($mot_de_passe, $user_log['password'])) {
				$tab = [];
				foreach ($user_log as $key => $value) {
					if (!is_numeric($key) && $key != 'password' && $key != 'id' && $key != 'mail') {
						$tab['user'][$key] = $value;
					}
				}
				if (isset($_GET['url']) && !empty($_GET['url'])) {
					$encryption_key_256bit = base64_encode(openssl_random_pseudo_bytes(32));
					$url = $_GET['url']."?acces_token=".urlencode(my_encrypt(serialize($tab), $encryption_key_256bit)).'&pass='.urlencode($encryption_key_256bit);
					header("Location:".$url);
				} else {
					$erreur = $api_crash;
				}
			} else {
				$erreur = $api_erreur_login;
			}
		} else {
			$erreur = $api_erreur_login;
		}
	}
}

require_once "template/index.php";
?>