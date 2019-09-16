<?php

Class movie_pal {

	/**
	 * @bdd Object
	 */
	private $bdd;

	/**
	 * @torrent_url String
	 */
	private $torrent_url;

	/**
	 * @torrent_name String
	 */
	private $torrent_name;

	/**
	 * @convert String
	 */
	private $convert;

	const LINK_DIR = "/opt/lampp/htdocs/www/hypertube/torrent";
	const CONV_DIR = "/download_target/";
	const VIT_MOYENNE_CO = 2000;

	public function __Construct(Object $bdd, String $torrent_url) {

		$this->bdd = $bdd;
		$this->torrent_url = $torrent_url;

		$torrent = new Torrent($this->torrent_url);
		$this->torrent_name = $torrent->name();
		$this->convert = str_replace(".avi", ".mkv", $this->torrent_name);
		$this->is_finish_convert();

	}

	private function is_finish_convert() {

		if ($this->state() == 2) {
			exec("ps aux", $return_task);
			$return_task = implode("", $return_task);
			if (!preg_match("#".str_replace("[ Torrent9.cz ]", "", $this->torrent_name)."#", $return_task)) {
				
				$change_stat = $this->bdd->prepare('UPDATE films SET download = ? WHERE torrent_url = ?');
				$change_stat->execute(array(3, $this->torrent_url));
			}
		}
	}

	public function get_name() : String {

		return ($this->torrent_name);
	}

	public function get_conv() : String {

		return ($this->convert);
	}

	public function state() : Int {

		$state = $this->bdd->prepare("SELECT download FROM films WHERE torrent_url = ?");
		$state->execute([$this->torrent_url]);
		return ($state->fetch()['download']);
	}

	public function download() : Int {

		if (!file_exists(self::LINK_DIR))
			mkdir(self::LINK_DIR);
		$name = explode("/", $this->torrent_url);
		$name = $name[count($name) - 1];
		if (!file_exists(self::LINK_DIR.'/'.$name)) {
			$this->torrent_url = str_replace(" ", "%20", $this->torrent_url);
			$do = "wget ".$this->torrent_url." -P ".self::LINK_DIR;
			shell_exec($do);
			if (!file_exists(self::LINK_DIR.'/'.$name))
				return (404);
			$change_stat = $this->bdd->prepare('UPDATE films SET download = ? WHERE id = ?');
			$change_stat->execute(array(1, $_SESSION['id_film']));
			return (1);
		}
		return (0);
	}

	public function convert() {

		shell_exec("nohup ffmpeg -i /download/'".$this->torrent_name."' -speed 1000 -movflags +faststart -preset ultrafast /download_target/'".$this->convert."' -y >/dev/null 2>&1 &");
		$change_stat = $this->bdd->prepare('UPDATE films SET download = ? WHERE torrent_url = ?');
		$change_stat->execute(array(2, $this->torrent_url));
	}

	public function go_seen(Int $in_convertion) {

		if ($in_convertion == 0)
			$_SESSION['buff_size'] = 502400;
		else
			$_SESSION['buff_size'] = 1000;

		$filePath = self::CONV_DIR.$this->convert;

		if (file_exists($filePath)) {
			$is_in = $this->bdd->prepare("SELECT * FROM vu_movies WHERE id_user = ? AND id_movie = ?");
			$is_in->execute(array($_SESSION['id'], $_SESSION['id_film']));
			if ($is_in->rowCount() == 0) {
				$vu_movie = $this->bdd->prepare("INSERT INTO vu_movies(id_user, id_movie, temps) VALUES(?, ?, ?)");
				$vu_movie->execute(array($_SESSION['id'], $_SESSION['id_film'], time()));
			} else {

				$vu_movie = $this->bdd->prepare("UPDATE vu_movies SET temps = ? WHERE id_user = ? AND id_movie = ?");
				$vu_movie->execute([time(), $_SESSION['id'], $_SESSION['id_film']]);
			}
			$window = new init_film($filePath, $_SESSION['buff_size'], $_SESSION['id']);
		} else {
			echo "Le film n'existe pas ou plus";
		}
	}
}

?>
