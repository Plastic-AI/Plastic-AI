<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detect_plastic extends Titan_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$layout = array(
			'page'            => 'forms/detect-plastic',
			'title'           => 'Detect Plastic',
		);
		$this->layout->view($layout);
	}
}