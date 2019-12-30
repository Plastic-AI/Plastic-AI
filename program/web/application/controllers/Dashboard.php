<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Titan_Controller {
	public function __construct() {
		parent::__construct();

		$this->current_domain = base_url();
		$this->load->model('mod_status');
		$this->load->model('mod_checking_log');
	}

	public function index($page = false) {
		$domain = explode("/", $this->current_domain);
		if(!$page) {
			$layout = array(
				'page'      	=> 'pages/dashboard',
				'title'     	=> 'Dashboard',
				'arr_data'		=> $this->mod_checking_log->get_results(array('user_login' => $this->user_login), 1, 5)
			);

			$this->layout->view($layout);
		} elseif($page == 'status') {
			$layout = array(
				'memory_usage'	=> $this->mod_status->get_server_memory_usage(),
				'cpu_usage'		=> $this->mod_status->get_server_cpu_usage(),
				'load_avg'		=> $this->mod_status->get_server_load_avg(),
				'response_time'	=> $this->mod_status->get_server_response_time($domain[2]),
			);

			$this->load->view('autoload/status', $layout);
		}
	}
}