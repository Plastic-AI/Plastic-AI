<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Req_log extends Titan_Req_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('mod_change_log');
	}

	public function get_by_controller_action($page = 1) {
		$_url = $this->input->get('url');

		if (!$_url) {
			$this->ajax_output('', 400, 'Bad Request');
		}

		$page          = is_numeric($page) ? $page : 1;
		$data_per_page = 20;

		$where['url_like'] = $_url;
		$arr_log           = $this->mod_change_log->get_results($where, $page, $data_per_page);

		$result = array();
		foreach ($arr_log as $value) {
			$result[] = array(
				'id'         => $value->log_id,
				'created'    => timestamp_to_date($value->created),
				'created_by' => $value->created_by,
				'ip'         => $value->ip
			);
		}
		$this->ajax_output($result, 200);
	}
}