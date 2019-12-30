<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Change_logs extends Titan_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model('mod_change_log');
	}

	public function index($page_number = 1) {
		$_created_by = $this->input->get('txtCreatedBy');
		$_page		 = $this->input->get('txtPage');
		$_action	 = $this->input->get('txtAction');
		$_ip		 = $this->input->get('txtIP');
		$_date_from	 = $this->input->get('txtDateFrom', TRUE);
		$_date_to	 = $this->input->get('txtDateTo', TRUE);

		$where['created_by'] = $_created_by;
		$where['controller'] = $_page;
		$where['action'] 	 = $_action;
		$where['ip'] 		 = $_ip;
		if(!empty($_date_from) && !empty($_date_to) && $_date_from == $_date_to) {
			$where['date_now'] = date_to_timestamp($_date_from);
		} else {
			if(!empty($_date_from)) {
				$where['date_from']	= date_to_timestamp($_date_from);
			}
			if(!empty($_date_to)) {
				$where['date_to'] = date_to_timestamp($_date_to);
			}
		}

		$page_number    = is_numeric($page_number) ? $page_number : 1;
		$data_per_page  = 20;
		$arr_log		= $this->mod_change_log->get_results($where, $page_number, $data_per_page);

		$this->load->config('pagination', TRUE);
		$conf_pg               = $this->config->item('pagination');
		$conf_pg['total_rows'] = $this->mod_change_log->total_rows;
		$conf_pg['per_page']   = $data_per_page;
		$conf_pg['base_url']   = base_url($this->router->fetch_class() . '/' . $this->router->fetch_method());
		$this->pagination->initialize($conf_pg); // INIT

		$layout = array(
			'page'      => 'pages/change-logs',
			'title'     => 'Change Logs',
			'arr_log' 	=> $arr_log
		);

		$this->layout->view($layout);
	}
}