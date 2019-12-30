<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apidocs extends Titan_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$layout = array(
			'page'            => 'pages/api-documentation',
			'title'           => 'API Documentation',
		);
		$this->layout->view($layout);
	}
}