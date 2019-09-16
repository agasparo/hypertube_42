<?php

require_once "class/setup.class.php";

if (!isset($bdd))
	require '../modules/bdd.php';

$init = new Bdd_manager\Setup($bdd);
header("Location:http://192.168.99.100.xip.io:41062/www/hypertube/");

?>