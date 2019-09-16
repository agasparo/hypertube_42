<?php
session_start();

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
	if (isset($_POST['change_infos']) && !empty($_POST['change_infos'])) {
		require 'bdd.php';
		$tab = ['in_util_name', 'in_surname', 'in_name', 'exampleInputEmail1', 'in_pass'];
		$tabs = ['user_login', 'prenom', 'nom', 'mail', 'password'];
		foreach ($tab as $value) {
			if (is_modif($value, $bdd))
				modif_database($bdd, $_POST[$value], $tabs[array_search($value, $tab)]);
		}
		header("Location:../user/".$_SESSION['id']);
	}
}

function is_modif($value, $bdd) {
	if (empty($_POST[$value]))
		return (0);
	require_once '../class/userinfos.class.php';
	$user = new userinfos($_SESSION['id'], $bdd);
	if ($user->check_id() == 0)
		return (0);
	return (1);
}

function modif_database($bdd, $value, $modif) {
	if ($modif == "password") {

		if (!check_mdp($value))
			return (0);
		require_once '../class/crypt.class.php';
		$mdp = new crypt();
		$value = $mdp->encrypt($value);
	}
	$change_val = $bdd->prepare("UPDATE membre SET ".$modif." = ? WHERE id = ?");
	$change_val->execute(array($value, $_SESSION['id']));
}

function check_mdp($show_mdp) {
    $test = preg_replace('/[1234567890]/iu', "", $show_mdp);
    if (ctype_alnum($show_mdp) AND preg_match('/([0-9]+)/', $show_mdp) AND ctype_alpha($test))
        return (1);
    return (0);
}
?>