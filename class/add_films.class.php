<?php

Class add_films {

	/**
	 * @film_name String
	 */
	private $film_name;

	/**
	 * @res String
	 */
	private $res;

	/**
	 * @def String
	 */
	private $def;

	/**
	 * @tab_val Array
	 */
	private $tab_val = ["TRUEFRENCH", "HDCAM", "FRENCH", "FANSUB", "BDRip", "English", "ENG", "720p", "HDRip", "WEBRip", "1080p", "DVDRIP", "hd", "ts", "xvid", "vo", "en", "streaming", "tc", "dvdscr", "md", "HDlight", "(Octalogie)", "WEBRIP", "Hindi", "MD", "CAM", "HDRIP", "HDTS", "HDCAM-Rip", "HDTC", "XviD", "TS", "WEB-DL", "VOSTFR", "AC3-EVO", "Movies", "HQ", "HD-TC", "BDRiP"];

	/**
	 * @sources Array
	 */
	private $sources = ["https://ww6.cpasbien9.net/chercher-un-film", "https://www6.cpasbien9.net/chercher-un-film"];

	/**
	 * @alive Int
	 */
	private $alive = 0;

	public function __Construct(String $new_film) {

		$this->film_name = strtolower(str_replace("-", " ", $new_film));
		$this->def = $this->film_name;
		$request = new http_request('POST', array("mot_search" => $new_film));

		foreach ($this->sources as $value) {
			
			$this->res = $request->send_request($value);
			preg_match_all("/<a\s[^>]*href=([\"\']??)([^\" >]*?)\\1[^>]* class=\"titre\">(.*)<\/a>/siU", $this->res, $matches);
			if (count($matches[2]) < 30) {
				$this->alive = $this->res;
				break;
			}
		}
	}

	public function fetch(Object $bdd, String $no_res) {

		if (empty($this->alive)) {
			$str = $no_res." : '".$this->def."'";
			echo json_encode($str);
			return (0);
		} else
			$this->res = $this->alive;

		$tab = [];

		preg_match_all("/<a\s[^>]*href=([\"\']??)([^\" >]*?)\\1[^>]* class=\"titre\">(.*)<\/a>/siU", $this->res, $matches);

		$i = 0;

		foreach ($matches[2] as $value) {

			if ($i == 15)
				break;
	
			$html = file_get_contents($value);

			preg_match_all('/<img src="(.*)" alt="(.+)">/', $html, $picture);
			$link_img = $picture[1][0];

			if ($this->url_exists($link_img) && !empty($link_img) && is_array(getimagesize($link_img))) {

				preg_match_all('#<a href="'.$value.'" title="(.+)">(.+)</a>#', $html, $title);
				$this->film_name = $this->epur_name($title[2][0]);

				preg_match_all('#<a href="https://cestpasbien.pro/torrents/(.+).torrent">(.*)</a>#', $html, $link_torrent);
				if (isset($link_torrent[1][0])) {
					$link_torrent = "https://cestpasbien.pro/torrents/".$link_torrent[1][0].".torrent";
					$tab[] = $this->do_content($link_img, $link_torrent, $bdd);
				}
			}
			$i++;
		}
		$str = $no_res." : '".$this->def."'";
		if (empty($tab) || empty($tab[0]))
			echo json_encode($str);
		else
			echo json_encode($tab);
	}

	public function epur_name(String $str) : String {

		$e = explode(" ", $str);
		$i = 0;
		while (isset($e[$i])) {
			if (in_array(strtoupper($e[$i]), $this->tab_val))
				unset($e[$i]);
			if (isset($e[$i]) && in_array($e[$i], $this->tab_val))
				unset($e[$i]);
			$i++;
		}
		return (implode(" ", $e));
	}

	public function do_content(String $img, String $torrent, Object $bdd) : String {

		require '../panel/control.panel.php';
		$is = $bdd->prepare("SELECT * FROM films WHERE name = ?");
		$is->execute(array($this->film_name));
		if ($is->rowCount() == 0) {
			$req_film_insert = $bdd->prepare("INSERT INTO films(name, torrent_url, img, resumer, note, nb_vote, auteur, temps_films, date_prod, casting, download) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$req_film_insert->execute([$this->film_name, $torrent, $img, "", 0, 0, "", "", "", "", 0]);
			$show_film = $bdd->prepare("SELECT * FROM films WHERE torrent_url = ?");
			$show_film->execute(array($torrent));
			$films_infos = $show_film->fetch();
			$content = file_get_contents('../template/galerie.php');
			$content = str_replace('{{img_link}}', $img, $content);
			$content = str_replace('{{titre_film}}', $this->film_name, $content);
			$content = str_replace('{{id_film}}', $films_infos['id'], $content);
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
			$content = str_replace('{{s_film}}', $s_film, $content);
			return (str_replace('{{note_film}}', $note, $content));
		}
		return ("");
	}

	public function url_exists(String $url_a_tester) {

		$F = @fopen($url_a_tester,"r");

		if($F) {
			fclose($F);
			return true;
		}
		else return false;
	}

}

?>