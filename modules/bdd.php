<?php

$DB_DSN = 'mysql:host=localhost;dbname=hypertube;charset=utf8';
$DB_USER = 'root';
$DB_PASSWORD = '';

try {

	$bdd = new PDO($DB_DSN , $DB_USER, $DB_PASSWORD);

} catch (Exception $error) {

	if (preg_match("#Unknown database 'hypertube'#", $error->getMessage())) {

		$db_error = new PDO("mysql:host=localhost;charset=utf8", $DB_USER, $DB_PASSWORD);
		$db_error->exec('CREATE DATABASE hypertube');

	}
}

?>