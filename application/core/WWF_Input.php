<?php

class WWF_Input extends CI_Input {
	private $qs = array();

	public function __construct() {
		parent::__construct();

		if (isset($_SERVER['REDIRECT_QUERY_STRING'])) {
			$params = explode("&", $_SERVER["REDIRECT_QUERY_STRING"]);

			for ($i = 0; $i < sizeof($params); $i++) {
				list($key, $value) = explode("=", $params[$i]);

				if (!is_null($value) && $value != "") {
					$this -> qs[$key] = $value;
				}
			}
		}
	}

	public function query_string($key) {
		if (array_key_exists($key, $this -> qs)) {
			return $this -> qs[$key];
		} else {
			return false;
		}
	}
}
