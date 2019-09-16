<?php
if (!isset($_SESSION))
	session_start();

if (isset($_SESSION['chargement_film']) && !empty($_SESSION['chargement_film'])) {
	$inf = explode("/", $_SESSION['chargement_film']);
	if (isset($inf[1]) && $inf[1] == "download_torrent") {
		$wi = rand(1, 4);
		?>
		<body style="background-color: black;">
			<video onended="myFunction()" autoplay height="95%" width="100%">
				<source src="<?= '../pub/pub'.$wi.'.mp4'; ?>" type="video/mp4">
			</video>
		</body>
		<?php
	} else if (isset($inf[1]) && $inf[1] == "download") {
		?>
		<body style="background-image: url('../photos/loading_video.gif'); background-position: center; background-repeat: no-repeat;">
		</body>
		<?php
		if (file_exists($_SESSION['chargement_film'])) {

			$e = stat($_SESSION['chargement_film']);
			if ($_SESSION['etat_film'] == "none")
				$_SESSION['etat_film'] = $e[10];
			else if ($_SESSION['etat_film'] == $e[10])
				header("Location:stream.php?play=1");
			else
				$_SESSION['etat_film'] = $e[10];
		}
		?><META http-equiv="refresh" content="5; URL=chargement.php"><?php
	}
} else {
	header("Location:stream.php");
}

?>
<script>
function myFunction() {
  window.location = "stream.php?play=1";
}
</script>
