<?php

require '../modules/bdd.php';
require '../panel/control.panel.php';

if (!isset($_SESSION))
	session_start();

if (isset($_POST['tab_val'])) {
	foreach ($_POST['tab_val'] as $key => $value) {
		$_POST[$key] = htmlspecialchars($value);
	}
}

$tab_rep['success'] = 1;
$tab_rep['response'] = "";

if (isset($_POST['tab_val'])) {
	if ($_POST['tab_val'][0] == 'img_form')
		echo json_encode(finish_ins($bdd));
	else if($_POST['tab_val'][2] == "pass_from")
		echo json_encode(reset_pass($bdd, $_POST['tab_val'], $reset_error, $s, $corps));
	else if ($_POST['tab_val'][2] == 'co_form')
		echo json_encode(verif_connexion($bdd, $_POST['tab_val'], $tab_rep, $form_error_remp, $co_error));
	else if ($_POST['tab_val'][6] == 'ins_form')
		echo json_encode(verif_ins($bdd, $_POST['tab_val'], $tab_rep));
}

function reset_pass($bdd, $table, $reset_error, $s, $corps) {
	$is_user = $bdd->prepare("SELECT * FROM membre WHERE user_login = ? AND mail = ?");
	$is_user->execute(array($table[0], $table[1]));
	$userinfos = $is_user->fetch();
	if ($is_user->rowCount() == 0) {
		$tab_rep['success'] = 0;
		$tab_rep['responce'] = $reset_error;
		return ($tab_rep);
	}
	$pass = create_pass(8);
	require '../class/crypt.class.php';
	$e = new crypt();
	$update_pass = $bdd->prepare("UPDATE membre SET password = ? WHERE user_login = ?");
	$update_pass->execute(array($e->encrypt($pass), $table[0]));
	require '../mail/index.php';
	$msg = $s." ".$userinfos['prenom']." ".$corps." ".$pass;
	file_put_contents('../mail/content.html', $msg);
	test_gmail_smtp_basic($table[1], 'Mot de passe oublie', $userinfos['prenom']);
	$tab_rep['success'] = 1;
	return ($tab_rep);
}

function create_pass($length) {
	$caracteres = 'abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$longueurMax = strlen($caracteres);
	$chaineAleatoire = '';
	$i = 0;
	while ($i < $length) {
		$chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
		$i++;
	}
	return ($chaineAleatoire);
}

function finish_ins($bdd) {
	$req_user = $bdd->prepare("SELECT * FROM membre WHERE user_login = ?");
	$req_user->execute(array($_SESSION['connect_img']));
	$user = $req_user->fetch();
	$_SESSION['id'] = $user['id'];
	$_SESSION['lang'] = "en_US";
	unset($_SESSION['connect_img']);
	$req_admin = $bdd->prepare("SELECT * FROM admin WHERE id_user = ?");
	$req_admin->execute([$_SESSION['id']]);
	if ($req_admin->rowCount() > 0)
		$_SESSION['admin'] = 1;
	$tab_rep['success'] = 1;
	return ($tab_rep);
}

function verif_ins($bdd, $tab, $tab_rep) {
	require '../panel/control.panel.php';
	if (isset($tab[0]) && !empty($tab[0]) && isset($tab[1]) && !empty($tab[1]) && isset($tab[2]) && !empty($tab[2]) && isset($tab[3]) && !empty($tab[3]) && isset($tab[4]) && !empty($tab[4]) && isset($tab[5]) && !empty($tab[5])) {
		if (strlen($tab[0]) < 6) {
			$tab_rep['success'] = 0;
			$tab_rep['responce'] = $usr_login_error;
			return ($tab_rep);
		}
		if (preg_match("# #", $tab[0])) {
			$tab_rep['success'] = 0;
			$tab_rep['responce'] = $usr_login_error_space;
			return ($tab_rep);
		}
		$req_user = $bdd->prepare("SELECT * FROM membre WHERE user_login = ?");
		$req_user->execute(array($tab[0]));
		if ($req_user->rowCount() > 0) {
			$tab_rep['success'] = 0;
			$tab_rep['responce'] = $usr_login_error_p;
			return ($tab_rep);
		}
		if (strlen($tab[1]) < 3) {
			$tab_rep['success'] = 0;
			$tab_rep['responce'] = $name_error;
			return ($tab_rep);
		}
		if (strlen($tab[2]) < 3) {
			$tab_rep['success'] = 0;
			$tab_rep['responce'] = $surname_error;
			return ($tab_rep);
		}
		if (!filter_var($tab[3], FILTER_VALIDATE_EMAIL)) {
			$tab_rep['success'] = 0;
			$tab_rep['responce'] = $mail_error;
			return ($tab_rep);
		}
		if (!check_mdp($tab[4])) {
			$tab_rep['success'] = 0;
			$tab_rep['responce'] = $mdp_error;
			return ($tab_rep);
		}
		if ($tab[4] != $tab[5]) {
			$tab_rep['success'] = 0;
			$tab_rep['responce'] = $mdps_error;
			return ($tab_rep);
		}
		insert_in_bdd($bdd, $tab);
		$tab_rep['success'] = 2;
		$tab_rep['responce'] = remp_values(['inscription'], file_get_contents('../template/insert_img.php'));
		$_SESSION['connect_img'] = $tab[0];
		return ($tab_rep);
	} else {
		$tab_rep['success'] = 0;
		$tab_rep['responce'] = $form_error_remp;
		return ($tab_rep);
	}
}

function insert_in_bdd($bdd, $table) {
	require '../class/crypt.class.php';
	$e = new crypt();
	unset($table[6]);
	$table[5] = "en";
	$table[4] = $e->encrypt($table[4]);
	$table[6] = 0;
	$insert_user = $bdd->prepare('INSERT INTO membre(user_login, prenom, nom, mail, password, lang, api) VALUES(?, ?, ?, ?, ?, ?, ?)');
	$insert_user->execute($table);
}

function check_mdp($show_mdp) {
    $test = preg_replace('/[1234567890]/iu', "", $show_mdp);
    if (ctype_alnum($show_mdp) AND preg_match('/([0-9]+)/', $show_mdp) AND ctype_alpha($test))
        return (1);
    return (0);
}

function verif_connexion($bdd, $tab, $tab_rep, $form_error_remp, $co_error) {
	require '../class/crypt.class.php';
	$e = new crypt();
	if (isset($tab[0]) && !empty($tab[0]) && isset($tab[1]) && !empty($tab[1])) {
		$req_connexion = $bdd->prepare('SELECT * FROM membre WHERE user_login = ?');
		$req_connexion->execute(array($tab[0]));
		if ($req_connexion->rowCount() == 0) {
			$tab_rep['success'] = 2;
			$tab_rep['responce'] = remp_values(['util_name', 'surname', 'name', 'mail', 'mdp', 'mdp_rep', 'inscription', 'input_util', 'input_name', 'input_surname', 'input_mail', 'input_mdp'], file_get_contents('../template/ins.php'));
			return ($tab_rep);
		} else {
			$req_connexion = $bdd->prepare('SELECT * FROM membre WHERE user_login = ?');
			$req_connexion->execute(array($tab[0]));
			$co = 0;
			while ($auth = $req_connexion->fetch()) {
				if($e->compare($tab[1], $auth['password'])) {
					$co = 1;
					$infs = $auth;
					break;
				}
			}
			if ($co == 0) {
				$tab_rep['success'] = 0;
				$tab_rep['responce'] = $co_error;
				return ($tab_rep);
			} else {
				$get_img = $bdd->prepare("SELECT * FROM img_users WHERE id_user = ?");
				$get_img->execute(array($infs['id']));
				if ($get_img->rowCount() == 0) {
					$tab_rep['success'] = 2;
					$tab_rep['responce'] = remp_values(['inscription'], file_get_contents('../template/insert_img.php'));
					$_SESSION['connect_img'] = $infs['user_login'];
					return ($tab_rep);
				} else {
					$req_user = $bdd->prepare("SELECT * FROM membre WHERE user_login = ?");
					$req_user->execute(array($tab[0]));
					$user = $req_user->fetch();
					$_SESSION['id'] = $user['id'];
					$_SESSION['lang'] = $user['lang'];
					$req_admin = $bdd->prepare("SELECT * FROM admin WHERE id_user = ?");
					$req_admin->execute([$_SESSION['id']]);
					if ($req_admin->rowCount() > 0)
						$_SESSION['admin'] = 1;
					$tab_rep['success'] = 1;
					return ($tab_rep);
				}
			}
		}
	} else {
		$tab_rep['success'] = 0;
		$tab_rep['responce'] = $form_error_remp;
		return ($tab_rep);
	}
}

function remp_values($tab, $file) {
	require '../panel/control.panel.php';
	foreach ($tab as $key => $value) {
		$file = str_replace('{{'.$value.'}}', ${$value}, $file);
	}
	return ($file);
}

?>