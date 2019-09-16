<?php

Class userinfos {
	
	/**
	 * @id Int
	 */
	private $id;

	/**
	 * @bdd Object
	 */
	private $bdd;

	/**
	 * @inf Array
	 */
	private $inf = [];

	public function __Construct(Int $user_id, Object $bdd) {
		$this->id = $user_id;
		$this->bdd = $bdd;
		if ($user_id != $_SESSION['id']) {
			$new_pro = $bdd->prepare("INSERT INTO vu(id_user) VALUES(?)");
			$new_pro->execute(array($_SESSION['id']));
		}
	}

	public function check_id() : Int {

		$req = $this->bdd->prepare("SELECT * FROM membre WHERE id = ?");
		$req->execute(array($this->id));
		if ($req->rowCount() == 0)
			return (0);
		return (1);
	}

	public function set_infos() {
		$req = $this->bdd->prepare("SELECT * FROM membre WHERE id = ?");
		$req->execute(array($this->id));
		$this->inf = $req->fetch();
	}

	public function get_usernme_inf() : String {
		return ($this->inf['prenom']." ".$this->inf['nom']);
	}

	public function get_lang() : String {
		$l = explode("_", $this->inf['lang']);
		return ($l[0]);
	}

	public function get_all_infs(Array $tab, String $lang_choice) : String {
		$html_head = '<table class="table table-hover"><tr>';
		$html_left = '<td style="vertical-align:middle;"><img src="'.$this->get_img().'" class="img_user" id="img_change"></td>';
		$html_right = '<td>'.$this->llang($lang_choice).$this->form_do($tab).'</td></tr>';
		$html = $html_head.$html_left.$html_right;
		return ($html);
	}

	public function llang(String $lang_choice) : String {
		$html = '<div class="form-group">
		<label for="Select_lang">'.$lang_choice.'</label>
		<select class="form-control" id="Select_lang">';
		$data = file('data/language-codes.csv');
		foreach ($data as $value) {
			$value = explode(",", str_replace('"', '', $value));
			if ($this->get_lang() == $value[0])
				$html .= '<option selected="selected">';
			else
				$html .= '<option>';
			$html .= $value[1].'</option>';
		}
		$html .= '</select></div>';
		return ($html);
	}

	public function form_do(Array $tab) : String {
		$data = file_get_contents('template/form_user.html');

		if ($this->who_is() == 0) {
			$data = str_replace('{{mail_v}}', $tab[3], $data);
			$data = $this->bloque($data);
			$i = 0;
			while ($i < 3) {
				$tab[$i] = explode(" ", $tab[$i]);
				$tab[$i][0] = str_replace($tab[$i][0], '', $tab[$i][0]);
				$tab[$i] = implode(' ', $tab[$i]);
				$i++;
			}
			$data = str_replace('<button type="submit" class="btn btn-primary" id="change_infos" name="change_infos" value="user_form">{{valider}}</button>', '', $data);
		} else
			$data = str_replace('{{mail_v}}', $this->inf['mail'], $data);

		$data = str_replace('{{util_name}}', $tab[0], $data);
		$data = str_replace('{{surname}}', $tab[1], $data);
		$data = str_replace('{{name}}', $tab[2], $data);
		$data = str_replace('{{mail}}', $tab[3], $data);
		$data = str_replace('{{mdp}}', $tab[4], $data);
		$data = str_replace('{{valider}}', $tab[5], $data);

		$data = str_replace('{{util_name_v}}', $this->inf['user_login'], $data);
		$data = str_replace('{{surname_v}}', $this->inf['prenom'], $data);
		$data = str_replace('{{name_v}}', $this->inf['nom'], $data);
		$data = str_replace('{{mdp_v}}', $tab[4], $data);
		return ($data);
	}

	public function bloque(String $data) : String {
		return (str_replace('input type=', 'input disabled type=', $data));
	}

	public function who_is() : Int {
		if ($_SESSION['id'] == $this->id)
			return (1);
		return (0);
	}

	public function sub(Array $tabs, Array $tabs1) : String {
		$html_left = '<tr class="subts"><td>'.$this->add_subtitle($tabs).'</td>';
		$html_right = '<td>'.$this->histo_subtitle($tabs1).'</td></tr>';
		$html_bottom = '</table>';
		return ($html_left.$html_right.$html_bottom);
	}

	public function add_subtitle(Array $tab) : String {
		if ($this->who_is() == 1) {
			$data = file_get_contents('template/add_sub.html');
			$data = str_replace("{{add_sub}}", $tab[0], $data);
			$data = str_replace("{{des_sub}}", $tab[1], $data);
			$data = str_replace("{{ajouter}}", $tab[2], $data);
			$data = str_replace("{{body_select}}", $this->option(), $data);
			$data = str_replace("{{body_select_langue}}", $this->option_langue(), $data);
			$data = str_replace("{{nom_du_film}}", $tab[3], $data);
			$data = str_replace("{{langue}}", $tab[4], $data);
			return ($data);
		}
		return ("");
	}

	public function option() : String {
		$req_film = $this->bdd->query("SELECT * FROM films");
		$b = "";
		while ($film = $req_film->fetch()) {
			$b .= '<option value="'.$film['name'].'">'.$film['name'].'<option>';
		}
		return ($b);
	}

	public function option_langue() : String {
		$data = file('data/language-codes.csv');
		$b = "";
		foreach ($data as $value) {
			$value = explode(",", str_replace('"', '', $value));
			$b .= '<option value="'.$value[0].'">'.$value[1].'</option>';
		}
		return ($b);
	}

	public function histo_subtitle(Array $tab) : String {
		if ($this->who_is() == 1) {
			$data = file_get_contents('template/histo_sub.html');
			$data = str_replace("{{film_name}}", $tab[0], $data);
			$data = str_replace("{{date}}", $tab[1], $data);
			$subs = $this->bdd->prepare("SELECT * FROM sub WHERE id_user = ?");
			$subs->execute(array($_SESSION['id']));
			$i = 0;
			$html = "";
			while ($res = $subs->fetch()) {
				$html .= '<tr><th scope="row">'.$i.'</th>';
				$html .= '<td><a href="../films/'.$res['id_film'].'">'.$res["film_name"].'</a></td>';
				$html .= '<td>'.$res["time_s"].'</td>';
				$i++;
			}
			$data = str_replace("{{content}}", $html, $data);
			return ($data);
		}
		return ("");
	}

	public function get_img() : String {
		$req = $this->bdd->prepare("SELECT * FROM img_users WHERE id_user = ?");
		$req->execute(array($this->id));
		if ($req->rowCount() == 0)
			return ('../photos/icon.png');
		$img = $req->fetch();
		if (preg_match("#http://#", $img['img']) || preg_match("#https://#", $img['img']))
			return ($img['img']);
		return ('../photos/'.$img['img']);
	}

	public function get_recomp(Array $tab) : String {
		$html_head = '<table class="table table-hover"><tr>';
		$all_recp = new recomp();
		$html_body = $all_recp->get($tab, $this->id);
		$html_bottom = '</table>';
		return ($html_head.$html_body.$html_bottom);
	}

	public function get_view_movie(Int $id_u, Array $tab) : String {
		$get_f = $this->bdd->prepare("SELECT * FROM vu_movies WHERE id_user = ? ORDER BY temps DESC");
		$get_f->execute(array($id_u));
		$html_body = "";
		$i = 0;
		while ($ind = $get_f->fetch()) {
			$mo = $this->bdd->prepare("SELECT * FROM films WHERE id = ?");
			$mo->execute(array($ind['id_movie']));
			$name_m = $mo->fetch();
			$html_body .= '<tr><td>'.$i.'</td><td><a href="../films/'.$name_m["id"].'">'.$name_m['name'].'</a></td><td>'.gmdate("Y/m/j H:i:s", $ind['temps']).'</td></tr>';
			$i++;
		}
		$html_head = '<table class="table table-hover">';
		$html_head .= '<tbody><tr><th scope="col">id</th><th scope="col">'.$tab[0].'</th><th scope="col">'.$tab[1].'</th></tr></tbody>';
		$html_bottom = '</table>';
		return ($html_head.$html_body.$html_bottom);
	}

	public function get_percent_rewards(Int $id) : String {

		$get_if = $this->bdd->prepare("SELECT id FROM recomp WHERE id_user = ?");
		$get_if->execute([$id]);

		$recomp_count = count(file('data/rec.txt'));
		return (" ( ".($get_if->rowCount() / $recomp_count * 100)." % ) ");
	}

	public function get_percent_seen(Int $id) : String {

		$get_if = $this->bdd->prepare("SELECT id FROM vu_movies WHERE id_user = ?");
		$get_if->execute([$id]);

		$seen_count = $this->bdd->query("SELECT id FROM films");
		return (" ( ".(ceil($get_if->rowCount() / $seen_count->rowCount() * 100))." % ) ");
	}

	public function next_recomp(Int $id, String $rec_next) : String {

		$get_do = $this->bdd->prepare("SELECT * FROM recomp WHERE id_user = ?");
		$get_do->execute([$id]);

		$data = file('data/rec.txt');
		$c = count($data);

		while ($get_all = $get_do->fetch()) {

			unset($data[$get_all['id_recomp']]);
		}

		if (empty($data))
			return ("--");

		$index = key($data);
		$e = explode("_", $_SESSION['lang']);
		$tr = new traduction([array_values($data)[0]], $e[0], '');
		$dat = [array_values($data)[0]];
		while (count($dat) < $c)
			$dat[] = "";
		if ($e[0] == "fr")  {
			return ($rec_next." : ".$data[$index]." ( ".$this->get_percent($index, $id)." % ) ");
		}
		$return = $tr->set_trad_recp($e[0]."_rec", $dat);
		return ($rec_next." : ".$return[$index]." ( ".$this->get_percent($index, $id)." % ) ");

	}

	private function get_percent(Int $id, Int $id_user) : Int {

		$data = file('data/rec.txt');
		$tab_table = ["vu", "vu_movies"];

		$req = "SELECT id FROM ".$tab_table[$id]." WHERE id_user = ?";
		$get_p = $this->bdd->prepare($req);
		$get_p->execute([$id_user]);

		$e = explode(" ", $data[$id]);
		foreach ($e as $value) {
			if (is_numeric($value)) {
				$nb_to_go = $value;
				break;
			}
		}
		return (ceil($get_p->rowCount() / $nb_to_go * 100));
	}

}

?>