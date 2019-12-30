<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status extends Titan_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('mod_user');
	}

	public function index() {  
        $arr_user = $this->mod_user->get_row(array('user_login' => $this->user_login));
		$data = array(
            'user_balance'  => $arr_user->user_balance ? $arr_user->user_balance : '0'
		);

		$this->load->view('autoload/status-balance', $data);
    }
}