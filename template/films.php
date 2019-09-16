<?php
	if (!isset($_SESSION))
		session_start();

	if (!isset($film_name))
		header("Location:http://192.168.99.100.xip.io:41062/www/hypertube/");
	else {
		require 'modules/protect.php';
		require 'modules/ajj.php';
		require 'modules/tr.php';
		require 'class/rec.class.php';

		$new_re = new recomp();
		$new_re->set();
	}
?>
<html>
<head>
	<meta charset="utf-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/scss" href="<?= '../'.$link_scss; ?>">
	<link rel="stylesheet" type="text/scss" href="<?= '../'.$link_scss_1; ?>">
	<link rel="stylesheet" type="text/css" href="<?= '../'.$link_css; ?>">
	<link rel="stylesheet" type="text/css" href="<?= '../'.$link_css_1; ?>">
	<link rel="icon" type="image" href="<?= $film_img_cover;?>"/>
	<link rel="icon" href="<?= $film_img_cover;?>" />
	<title><?= $film_name; ?></title>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
		<a class="navbar-brand" href="<?= $_SESSION['id_film']; ?>"><?= $title_home." - ".$film_name; ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation" id="btnnav">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarColor01">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="../">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= '../user/'.$_SESSION['id']; ?>"><?= $profil; ?></a>
				</li>
				<li class="nav-item">
		         	<a class="nav-link" href="../modules/deco.php"><?= $deco; ?></a>
		        </li>
			</ul>
		</div>
	</nav>
	<h1 style="text-align: center; padding: 3%;"><?= $film_name; ?></h1>
	<?php
		if ($report_film > 0) {
			?><h6 style="text-align: center; color: #FF8C00;"><?= $att_sign." ".$report_film." ".$nb_re; ?></h6><?php
		}
	?>
	<table class="film_info">
		<tr>
			<th>
				<img src="<?= $film_img_cover;?>" class="img_cover">
			</th>
			<th>
				<div class="infs_more">
					<?= "<p>".$aut.$film_realisateur."</p>"; ?>
					<?= "<p>".$cast.$film_rcasting."</p>"; ?>
					<?= "<p>".$prod.$film_prod."</p>"; ?>
					<?= "<p>".$duration.$film_temps."</p>"; ?>
					<?= "<p>".$film_note."</p>"; ?>
					<?= "<p>".$nb_vote.$film_vote."</p>"; ?>
					<?= "<p>".$resu."<br><br>".$film_resu."</p>"; ?>
					<?= "<p>".$nb_views.$nb_view_film."</p>"; ?>
				</div>
			</th>
		</tr>
	</table>
	<div id="film_in">
		<iframe height="100%" width="100%" src="../modules/stream.php" id="stream"></iframe>
	</div>
	<div id="comment">
		<?php
			if ($report) {
				?><p><?= $signal_a; ?></p><?php
			} else {
				?><a href="../modules/dead_link.php"><?= $signal_dead; ?></a><br><br><?php
			}
			$txt = $film_name." on hypertube !! &url=http://192.168.99.100.xip.io:41062/www/hypertube/films/".$_SESSION['id_film']."&hashtags=".str_replace(" ", "_", $film_name)."_hypertube_42";
		?>
		<a class="twitter-share-button"
  			href="<?= 'https://twitter.com/intent/tweet?text='.$txt; ?>"
  			data-size="large"
  			target = "_blank">
		Tweet</a>
		<br>
		<br>
	</div>
	<div id="suggest">
		<h3><?= $sug; ?></h3>
		<?= $suggest; ?>
	</div>
	<div id="comment">
		<h3><?= $comm; ?></h3>
		<div class="form-group">
			<label for="post_comm_txt"><?= $comme_textarea; ?></label>
			<textarea class="form-control" id="post_comm_txt" rows="3" placeholder="<?= $comm_txt;?>"></textarea>
		</div>
		<button type="submit" class="btn btn-primary" id="post_comm"><?= $comm_env; ?></button>
		<div id="all_comment" style="margin-top: 3%;">
			<?php require 'modules/get_comment.php'; ?>	
		</div>
	</div>
	<div id="pub_1">
		<div id="pub_1_X">
			<p id="drop_pub_1">X</p>
			<font id="name_pub">Ordibg</font>
		</div>
		<div id="pub_messages">
			<p class="right_message_pub">
				<?= $txt_pub_1; ?>
			</p>
		</div>
		<input type="text" id="rep_pub_1">
		<button type="button" class="btn btn-primary btn-sm" id="post_pub_1"><?= $comm_env; ?></button>
	</div>
	<footer class="footer" >
		<br>
		  <h5><strong>Hypertube</strong></h5>
		  <p>Created by Agasparo, Lpelissi and others</p>
			 </p>
			 <p>Copyright 2019</p>
			 <br>
	</footer>
</body>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script type="text/javascript">
	var videoTime = "<?= $time_to_go; ?>";
</script>
<script type="text/javascript" src="<?= '../'.$link_js_film ;?>"></script>
<script type="text/javascript">
window.onbeforeunload = function(e) {
	let link = document.getElementById('stream').contentWindow.document.getElementsByTagName('source');
	if (link.length > 0) {
		$.post('../modules/modules_suppr.php', {src:link[0].src, video:document.getElementById('stream').contentWindow.document.body.querySelector('video').currentTime});
	}
	document.getElementById('stream').remove();
}
</script>
</html>