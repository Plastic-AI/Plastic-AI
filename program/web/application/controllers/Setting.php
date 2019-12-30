<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Titan_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model('mod_setting');
	}

	public function index() {
		$arr_setting = $this->mod_setting->get_row();

		$layout = array(
			'page'      	=> 'forms/setting',
			'title'     	=> 'Setting',
			'arr_setting'	=> $arr_setting
		);
		$this->layout->view($layout);
	}
}