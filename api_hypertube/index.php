<?php

session_start();

require 'vendor/autoload.class.php';


$redirect_url = 'http://192.168.99.100.xip.io:41062/www/api_hypertube/index.php';

$co = new Hypertube_connexion();
if (isset($_GET['acces_token']) && !empty($_GET['acces_token'])) {
	$_SESSION['acces_token_hypertube'] = $_GET['acces_token'].'||'.$_GET['pass'];
}
if (!isset($_SESSION['acces_token_hypertube']) || empty($_SESSION['acces_token_hypertube'])) {
	$url = 'http://192.168.99.100.xip.io:41062/www/hypertube/api/connexion.php?url='.urlencode($redirect_url);
	echo "<a href='".$url."'>Se connecter avec Hypertube</a>";
} else {
	echo "*********************************************************************<br>";
	echo "<br>";
	echo "user part";
	echo "<br>";
	echo "<br>*********************************************************************<br>";
	echo " get userinfos : <br><br>";
	$data = $co->get_user_data();
	$check = new verif($data);
	if (!$check->is_ok())
		echo $check->show_ch();
	else {
		$user = new user($data);
		echo " - name : ".$user->get_name()."<br>";
		echo " - surname : ".$user->get_surname()."<br>";
		echo " - language : ".$user->get_lang()."<br>";
		echo " - picture : ".$user->get_photo()."<br><br>";
		echo " update userinfos : <br>";
		echo " <br>- update name (ex : new_name = 'paul') : ";
		echo $user->update_name('paul');
		echo " <br>- update surname (ex : new_surname = 'Victor') : ";
		echo $user->update_surname('Victor');
		echo " <br>- update language (ex : new_language = 'Fr') : ";
		echo $user->update_lang('Fr');
		echo " <br>- update language (ex : new_language = 'All') : ";
		echo $user->update_lang('All');
		$url = "https://steakoverflow.42.fr/orders/Nc7z6W6KaRq9jpe8S";
		echo  "<br> - update picture (ex : new_picture = '".$url."') : ";
		echo $user->update_picture($url);
		echo "<br>*********************************************************************<br>";
		echo "<br>";
		echo "movies part";
		echo "<br>";
		echo "<br>*********************************************************************<br>";
		echo "<br> get all movies : <br><br>";
		$movie = new movie();
		echo $movie->show_all();
		echo "<br>";
		echo "<br> add movies : <br><br>";
		$film['name'] = "Pokémon Détective Pikachu TRUEFRENCH TS 2019";
		$film['torrent_url'] = "https://cestpasbien.pro/torrents/pokemon-detective-pikachu-truefrench-ts-2019.torrent";
		$film['picture'] = "https://www.filmze-streamiz.org/images/media/pokemon-detective-pikachu-truefrench-ts-2019.jpg";
		$film['resumer'] = "Après la disparition mystérieuse de Harry Goodman, un détective privé, son fils Tim va tenter de découvrir ce qui s’est passé.  Le détective Pikachu, ancien partenaire de Harry, participe alors à l’enquête : un super-détective adorable à la sagacité hilarante, qui en laisse plus d’un perplexe, dont lui-même. Constatant qu’ils sont particulièrement bien assortis, Tim et Pikachu unissent leurs forces dans une aventure palpitante pour résoudre cet insondable mystère.  À la recherche d’indices dans les rues peuplées de néons de la ville de Ryme – métropole moderne et tentaculaire où humains et Pokémon vivent côte à côte dans un monde en live-action très réaliste –, ils rencontrent plusieurs personnages Pokémon et découvrent alors un complot choquant qui pourrait bien détruire cette coexistence pacifique et menacer l’ensemble de leur univers."; 
		$film['note'] = "0";
		$film['nb_vote'] = "0";
		$film['auteur'] = "Rob Letterman";
		$film['time'] = "105";
		$film['production date'] = "2019";
		$film['casting'] = "Justice Smith, Kathryn Newton, Bill Nighy";
		foreach ($film as $key => $value) {
			echo "movie ".$key." --> ".$value."<br>";
		}
		echo "add movie : ".$movie->add($film);
		echo "<br><br>Remove movies : <br><br>";
		$id_movie = 4;
		echo "remove movie with id ".$id_movie." :".$movie->delete($id_movie, $user);
		echo $auto->help();
	}
}

?>