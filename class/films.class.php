<?php

class films {

	/**
	 * @inf Array
	 */
	private $films_infos = [];

	/**
	 * @req_nb Int
	 */
	private $req_nb = 0;

	/**
	 * @bdd Object
	 */
	private $bdd;

	public function set_infos(Int $id, Object $bdd) {
		$this->bdd = $bdd;
		$get_film = $bdd->prepare('SELECT * FROM films WHERE id = ?');
		$get_film->execute(array($id));
		if ($get_film->rowCount() > 0) {
			$this->films_infos = $get_film->fetch();
			$this->req_nb = $get_film->rowCount();
		}
	}

	public function get_name() : String {
		return ($this->films_infos['name']);
	}

	public function get_img_cover() : String {
		return ($this->films_infos['img']);
	}

	public function get_resumer() : String {
		return ($this->films_infos['resumer']);
	}

	public function get_realisateur() : String {
		return ($this->films_infos['auteur']);
	}

	public function get_casting() : String {
		return ($this->films_infos['casting']);
	}

	public function get_date_prod() : String {
		return ($this->films_infos['date_prod']);
	}

	public function get_temps_films() : String {
		$lHeure = floor($this->films_infos['temps_films'] / 60);
 		$lesMinutes = $this->films_infos['temps_films'] % 60;
 		if ($lesMinutes < 10)
 			$lesMinutes = "0".$lesMinutes;
		return($lHeure."h".$lesMinutes);
	}

	public function get_note(String $note_ob) : String {
		$note_obtenue = $this->films_infos['note'];
		$note = '<div class="rating">'.$note_ob;
		$a = 0;
		while ($a < $note_obtenue) {
			$note.= '<a href="#" class="note active_note">☆</a>';
			$a++;
		}
		while ($a < 10) {
			$note.= '<a href="#" class="note not_active_note">☆</a>';
			$a++;
		}
		$note .= '</div>';
		return ($note);
	}

	public function get_vote() : Int {
		return ($this->films_infos['nb_vote']);
	}

	public function get_download_state() : Int {
		return ($this->films_infos['download']);
	}

	public function get_torrent_link() : String {
		return ($this->films_infos['torrent_url']);
	}

	public function time_to_go() : Float {

		if (!isset($_SESSION['id_film']))
			return (0);

		$is = $this->bdd->prepare("SELECT * FROM time_f WHERE id_user = ? AND id_film = ?");
		$is->execute([$_SESSION['id'], $_SESSION['id_film']]);

		if ($is->rowCount() == 0)
			return (0);

		$time = $is->fetch();
		return (floatval($time['time']));
	}

	public function get_report() : Int {

		$is = $this->bdd->prepare("SELECT * FROM signaler WHERE id_user = ? AND id_film = ?");
		$is->execute([$_SESSION['id'], $_SESSION['id_film']]);
		if ($is->rowCount() == 0)
			return (0);
		return (1);
	}

	public function report_film() : Int {

		$is = $this->bdd->prepare("SELECT * FROM signaler WHERE id_film = ?");
		$is->execute([$_SESSION['id_film']]);
		return ($is->rowCount());
	}

	public function get_suggest(String $s_film, String $vu) : String {

		$get_film = $this->bdd->query("SELECT * FROM films ORDER BY RAND() LIMIT 3");
		$temp = "";
		while ($films_infos = $get_film->fetch()) {

			if (!file_exists('template/galerie.php'))
				$content = file_get_contents('../template/galerie.php');
			else
				$content = file_get_contents('template/galerie.php');
			$content = str_replace('{{img_link}}', $films_infos['img'], $content);
			$last_visit = $this->bdd->prepare("SELECT * FROM vu_movies WHERE id_movie = ? AND id_user = ?");
			$last_visit->execute(array($films_infos['id'], $_SESSION['id']));
			if ($last_visit->rowCount() > 0)
				$content = str_replace('{{titre_film}}', $films_infos['name'].$vu, $content);
			else
				$content = str_replace('{{titre_film}}', $films_infos['name'], $content);
			$content = str_replace('films/{{id_film}}', $films_infos['id'], $content);
			$notes = intval($films_infos['note']);
			$note = '<div class="rating" id="'.$films_infos['id'].'_note_re">';
			$a = 0;
			while ($a < $notes) {
				$note.= '<a href="#" id="'.$films_infos['id'].'_'.$a.'" class="note active_note">☆</a>';
				$a++;
			}
			while ($a < 10) {
				$note.= '<a href="#" id="'.$films_infos['id'].'_'.$a.'" class="note not_active_note">☆</a>';
				$a++;
			}
			$note .= '</div>';
			$content = str_replace('"gal_films"', '"gal_films sugg"', $content);
			$content = str_replace('{{note_film}}', $note, $content);
			$content = str_replace('{{s_film}}', $s_film, $content);
			$temp .= $content;
		}
		return ($temp);
	}

	public function view() : Int {

		$is = $this->bdd->prepare("SELECT * FROM vu_movies WHERE id_movie = ?");
		$is->execute([$_SESSION['id_film']]);
		return ($is->rowCount());
	}
}

?>