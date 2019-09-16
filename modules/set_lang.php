<?php

session_start();
require '../modules/bdd.php';

if (isset($_POST['lang']) && !empty($_POST['lang'])) {
	if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
		$lang = htmlspecialchars($_POST['lang']);
		$data = file('../data/language-codes.csv');
		$ok = 0;
		foreach ($data as $value) {
			$value = explode(",", str_replace('"', '', $value));
			if ($lang == trim($value[1])) {
				$ok = 1;
				break;
			}
		}
		if ($ok == 1) {
			$reqs = $bdd->prepare("UPDATE membre SET lang = ? WHERE id = ?");
			$reqs->execute(array($value[0], $_SESSION['id']));
			$_SESSION['lang'] = $value[0];
		} else
			exit(0);
	} else
		exit(0);
} else
	exit(0);

?>