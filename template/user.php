<?php

if (!isset($_SESSION['id']) || empty($_SESSION['id']))
	header("Location:../");
require 'class/userinfos.class.php';
require 'class/rec.class.php';

$u = new userinfos($id_user, $bdd);
if ($u->check_id() == 0)
	header("Location:../");
$u->set_infos();
$new_re = new recomp();
$new_re->set();
?>
<html>
<head>
	<meta charset="utf-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/scss" href="<?= '../'.$link_scss; ?>">
	<link rel="stylesheet" type="text/scss" href="<?= '../'.$link_scss_1; ?>">
	<link rel="stylesheet" type="text/css" href="<?= '../'.$link_css; ?>">
	<link rel="stylesheet" type="text/css" href="<?= '../'.$link_css_1; ?>">
	<link rel="icon" type="image/png" href="<?= '../'.$link_fav; ?>" />
	<script type="text/javascript" src="<?= '../'.$link_js_lib; ?>"></script>
	<script type="text/javascript" src="<?= $link_js_user; ?>"></script>
	<title><?= $title_home." - ".$u->get_usernme_inf(); ?></title>
	<link rel="icon" href="<?= $u->get_img(); ?>"/>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
		<a class="navbar-brand" href="<?= $_SESSION['id']; ?>"><?= $title_home." - ".$u->get_usernme_inf(); ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation" id="btnnav">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarColor01">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="../">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../modules/deco.php"><?= $deco; ?></a>
				</li>
			</li>
			</ul>
		</div>
	</nav>
	<?php
	echo $u->get_all_infs([$util_name, $surname, $name, $mail, $mdp, $val], $lang_choice);
	echo $u->sub([$add_sub, $des_sub, $ajj_btn, $nom_du_film, $langue_st], [$film_name_is, $dat_aj]);
	?>
	<br><h2 style="text-align: center;"><?= $rec_n.$u->get_percent_rewards($id_user); ?></h2>
	<h6 style="text-align: center; color: grey;"><?= $u->next_recomp($id_user, $rec_next); ?></h6>
	<br><?php
	echo $u->get_recomp([$rec_ob, $rec_n]);
	?><br><h2 style="text-align: center;"><?= $films_vu_l.$u->get_percent_seen($id_user); ?></h2><br><?php
	echo $u->get_view_movie($id_user, [$nom_du_film, $date_show]);
	?>
	<footer class="footer" >
		<br>
		  <h5><strong>Hypertube</strong></h5>
		  <p>Created by Agasparo, Lpelissi and others</p>
			 </p>
			 <p>Copyright 2019</p>
			 <br>
	</footer>
</body>
</html>