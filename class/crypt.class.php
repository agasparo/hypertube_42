<?php

Class crypt {

	/**
	 * @secret_key String
	 */
	private $secret_key;

	/**
	 * @secret_iv String
	 */
	private $secret_iv;

	/**
	 * @methode String
	 */
	private $methode;

	/**
	 * @all_methode Array
	 */
	private $all_methode;

	/**
	 * @val_iv Int
	 */
	private $val_iv;

	public function __Construct() {
		$this->all_methode = openssl_get_cipher_methods();
		$this->rm_useless_ciphers();
		$this->methode = $this->choose_methode();
		$this->secret_key = $this->generate_key_iv(32, 95, 0, 0);
		$this->val_iv = openssl_cipher_iv_length($this->methode);
		$this->secret_iv = $this->generate_key_iv(5, 16, 1, $this->val_iv);
	}

	private function rm_useless_ciphers() {
		$this->all_methode = array_filter( $this->all_methode, function($c) { return stripos($c,"des")===FALSE; } );
		$this->all_methode = array_filter( $this->all_methode, function($c) { return stripos($c,"rc2")===FALSE; } );
		$this->all_methode = array_filter( $this->all_methode, function($c) { return stripos($c,"rc4")===FALSE; } );
		$this->all_methode = array_filter( $this->all_methode, function($c) { return stripos($c,"md5")===FALSE; } );
		$this->all_methode = array_filter( $this->all_methode, function($c) { return stripos($c,"aes")===FALSE; } );
		$this->all_methode = array_values($this->all_methode);
	}

	private function choose_methode() : String {
		$max = count($this->all_methode) - 1;
		$min = 0;
		return ($this->all_methode[rand($min, $max)]);
	}

	private function generate_key_iv(Int $min, Int $max, Int $type, Int $val) : String {
		$length = rand($min, $max);
		$caracteres = 'abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$longueurMax = strlen($caracteres);
		$chaineAleatoire = '';
		$i = 0;
		while ($i < $length) {
			$chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
			$i++;
		}
		if ($type == 1)
			return (substr(hash('sha256', $chaineAleatoire), 0, $val));
		return (hash('sha256', $chaineAleatoire));
	}

	public function encrypt(String $data) : String {
		$hash = openssl_encrypt($data, $this->methode, $this->secret_key, 0, $this->secret_iv);
		if (empty($hash))
			$hash = openssl_encrypt($data, $this->methode, $this->secret_key, 0, $this->secret_iv);
		return ($this->crypt_methode().hash('sha256', $hash).$this->secret_key.$this->secret_iv);
	}

	public function compare(String $mdp_to_comp, String $mdp_ref) : Int {
		$methode = $this->get_methode($mdp_ref);
		$val_iv = $this->get_val_iv($methode);
		$iv = $this->get_iv($val_iv, $mdp_ref);
		$secret_mdp = $this->get_secret_mdp($mdp_ref, intval($val_iv));
		$comp = openssl_encrypt($mdp_to_comp, $methode, $secret_mdp, 0, $iv);
		$mdp = $this->rm_useless_infs($mdp_ref, intval($val_iv + strlen($secret_mdp)));
		if ($mdp == hash('sha256', $comp))
			return (1);
		return (0);
	}

	private function rm_useless_infs(String $mdp_ref, Int $out) : String {
		$new_mpd_ref = substr($mdp_ref, 0, -($out));
		return (substr($new_mpd_ref, 64));
	}

	private function get_methode(String $mdp_ref) {
		$new_mpd_ref = substr($mdp_ref, 0, 64);
		return ($this->decrypt_methode($new_mpd_ref));
	}

	private function decrypt_methode(String $mdp_ref) : String {
		$tab_m = openssl_get_cipher_methods();
		foreach ($tab_m as $value) {
			if (hash('sha256', $value) == $mdp_ref)
				return ($value);
		}
		return ('');
	}

	private function crypt_methode() : String {
		return (hash('sha256', $this->methode));
	}

	private function get_secret_mdp(String $mdp_ref, Int $out) : String {
		if ($out > 0)
			$new = substr($mdp_ref, 0, -($out));
		else
			$new = $mdp_ref;
		$new = substr($new, -64);
		return ($new);
	}

	private function get_iv(Int $val_iv, String $mdp_ref) : String {
		if ($val_iv == 0)
			return ('');
		return (substr($mdp_ref, -($val_iv)));
	}

	private function get_val_iv(String $methode) : Int {
		return (openssl_cipher_iv_length($methode));
	}
}

?>