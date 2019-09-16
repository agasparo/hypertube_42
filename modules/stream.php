<?php

session_start();

?>
<body style="background-color: black; color: white;">
<div style="height: 100%;">
<?php

if (isset($_SESSION['id']) && !empty($_SESSION['id']) && isset($_SESSION['id_film']) && !empty($_SESSION['id_film'])) {
	$id = $_SESSION['id_film'];
	require 'bdd.php';
	require '../panel/control.panel.php';
	require '../class/films.class.php';
	require '../class/stream.class.php';
	require '../class/init_film.class.php';
	require 'infos_films.php';
	require '../class/movie_pal.class.php';
	require '../class/torrent.class.php';

	if (isset($_GET['play']) && intval($_GET['play'] == 1)) {

		$mov = new movie_pal($bdd, $film_torrent_link);
		if ($mov->state() == 0) {
			if ($mov->download() == 404) {
				echo "<h1 style='position: relative; top: 45%;height: 5%; text-align: center;'>".$error_film_file."</h1>";
				return (0);
			}
			$_SESSION['etat_film'] = "none";
			$_SESSION['chargement_film'] = "/download/".$mov->get_name();
			header("Location:chargement.php");
		}
		if ($mov->state() == 1) {
			$mov->convert();
			$_SESSION['chargement_film'] = "/download_torrent/".$mov->get_conv();
			header("Location:chargement.php");
		}
		if ($mov->state() == 2)
			$mov->go_seen(1);
		else if ($mov->state() == 3)
			$mov->go_seen(0);

	} else {
		require '../template/fond.php';
	}
}

?>
</div>
</body>