<?php

require 'class/router.class.php';
require 'class/route.class.php';
require 'controler/controler.php';
require 'class/directory.class.php';

if (empty($_GET['url']))
	$_GET['url'] = '/';

$dir = new directory_check();
if (!$dir->check())
	header("Location:http://192.168.99.100.xip.io:41062/www/hypertube/");

$routeur = new Router($_GET['url']);
$routeur->get('/seed', 'post#show_seed');
$routeur->get('/', 'post#show_homepage');
$routeur->get('/:id_page', 'post#show_homepage');
$routeur->get('/films/:id', 'post#show_film');
$routeur->get('/user/:id', 'post#show_user');
$routeur->get('/setup/manage', 'post#show_setup');
$routeur->run();
?>