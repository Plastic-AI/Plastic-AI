<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Titan_Security extends CI_Security {
	public function __construct() {
		parent::__construct();
	}

	// overide default error
	public function csrf_show_error() {
		if (empty($_GET)) {
			$current_url = htmlspecialchars($_SERVER['REQUEST_URI']) . '?error=invalid-csrf';
		} else {
			$current_url = htmlspecialchars($_SERVER['REQUEST_URI']) . '&error=invalid-csrf';
		}

		header('Location: ' . $current_url, true, 303);
		exit;
	}
}