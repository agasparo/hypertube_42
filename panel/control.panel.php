<?php

//links

$link_css = "css/bootstrap.min.css";
$link_css_1 = "css/style.css";
$link_scss = "css/_variable.scss";
$link_scss_1 = "css/_bootstrap.scss";

$link_fav = "photos/icon.png";

$link_js_lib = "js/lib.js";
$link_js_film = "js/films.js";

$link_js_user = "../js/user.js";

$title_home = "Hypertube";

// template/home page

$link_js_home = 'js/home.js';
$link_js_troll = 'js/troll.js';

// template/co.php
$form_type = "Formulaire de connection / d'inscription";
$mail = "Adresse Mail";
$mdp = "Mot de passe";
$inscription = "S'inscrire";
$connection = "Se connecter";
$mdp_oubli = "mot de passe oublie";

// template/ins.php
$mdp_rep = "Repeter votre mot de passe";
$name = "Votre nom";
$surname = "Votre prenom";
$util_name = "Votre nom d'utilisateur";

// modules/connexion_ins.php
$form_error_remp = "Veuillez remplir tous les champs";
$usr_login_error = "Votre nom d'utilisateur dois faire au moins 6 caracteres";
$name_error = "Votre prenom dois faire au moins 3 caracteres";
$surname_error = "Votre nom dois faire au moins 3 caracteres";
$mail_error = "Mauvais mail";
$mdps_error = "Vos mots de passe ne concordent pas";
$mdp_error = "Votre mot de passe doit contenir lettres et chiffres";
$co_error = "Mauvais mail ou mauvais mot de passe";
$usr_login_error_p = "Ce pseudo est deja utilise";
$usr_login_error_space = "Il ne doit pas y avoir d'espace dans votre pseudo";

$val = "Valider";
$reset_error = "Mauvais mail ou mauvais nom d'utilisateur";

//form min_length
$input_util = 5;
$input_mdp = 5;
$input_name = 3;
$input_surname = 3;
$input_mail = 8;

//mudules galerie.php // pagination.php

$s_film = "Voir le film";

if (isset($bdd)) {
	$fims_par_page = 20;
	$film_total = $bdd->query("SELECT * FROM films");
	$film_total = $film_total->rowCount();
	$page_courrante = 0;
	$depart;
}

// template/trie.php

$trie_1 = "note";
$trie_2 = "nombre de vote";
$trie_3 = "annee de production";
$trie_4 = "temps du film";
$trie_5 = "nom";

// template/films.php

$comm = "Espace commentaire";
$comme_textarea = "Poster un commentaire";
$comm_env = "Poster";
$comm_txt = "Votre commentaire ...";

$resu = "Resumer : ";
$aut = "Realisateur : ";
$cast = "Casting : ";
$prod = "Date de sortie : ";
$duration = "Duree du film : ";
$nb_vote = "Nombre de vote : ";
$note_ob = "Note : ";
$post_on = "poster le ";

$profil = "Mon profil";
$deco = "Se deconnecter";
$search_it = "Rechercher";
$search_it_f = "film ...";

$signal_dead = "Signaler le lien comme mort";
$signal_a = " Vous avez deja signale ce lien";
$att_sign = "Attention ce film a etait signale";
$nb_re = "fois";
$sug = "Suggestions";
$nb_views = "Nombre de vues : ";

$txt_pub_1 = "Salut, tu recherche des ordis pas loin de chez toi ?";

//mail

$s = "Salut ";
$corps = " voici ton nouveau mot de passe : ";

// deja vue

$vu = "(deja vu)";

//user

$lang_choice = "Choisir une langue";
$add_sub = "Ajouter des sous-titres";
$des_sub = "Les fichiers autorises sont les .vtt et les .srt";
$film_name_is = "Titre du film";
$dat_aj = "Date d'ajout";
$ajj_btn = "Ajouter";
$nom_du_film = "Nom du film";
$langue_st = "Langue des sous-titres";
$rec_ob = "date obtention";
$rec_n = "Recompense";
$date_show = "date";
$films_vu_l = "Liste des films vu";
$rec_next = "Recompense suivante";

//404 not found

$oups = "Oups, tu tes perdu";
$home = "Home";

//connexion.php
$api_title = "Se connecter avec l'API";
$api_sub = "Connexion api";
$api_erreur_login = "Erreur Auth : Mauvais mot de passe ou mauvais nom d'utilisateur";
$api_crash = "Erreur 30 : requette crash";

//add_films
$no_res = "Pas de resultat trouve pour";

//stream
$error_film_file = "Le fichier demande n'est pas disponible";


if (!isset($_SESSION))
	session_start();

if (!file_exists('class/trad.class.php')) {
	$path = '../';
	require_once '../class/trad.class.php';
} else {
	$path = "";
	require_once 'class/trad.class.php';
}

if (!isset($_SESSION['lang']) || empty($_SESSION['lang']))
	$_SESSION['lang'] = 'en_US';

if (isset($_SESSION['lang'])) {
	$l = explode("_", $_SESSION['lang']);
	if ($l[0] != 'fr') {
		$trad = new traduction(get_defined_vars(), $_SESSION['lang'], $path);
		$arr = $trad->set_trad();
		if (is_array($arr))
			extract($arr);
	}
}

?>