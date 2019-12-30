<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detection_logs extends Titan_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model('mod_checking_log');
	}

	public function index($page_number = 1) {
		$plastic_type	= $this->input->get('txtPlasticType');
		$date_from 		= $this->input->get('txtDateFrom', TRUE);
		$date_to 		= $this->input->get('txtDateTo', TRUE);
		$status 		= is_numeric($this->input->get('cbStatus', TRUE)) ? $this->input->get('cbStatus', TRUE) : '';
		$order_by		= $this->input->get('cbOrderBy') ? $this->input->get('cbOrderBy') : 'DESC';

        $where['plastic_type'] 		= $plastic_type;
		$where['plastic_status'] 	= $status;
		if(!empty($date_from) && !empty($date_to) && $date_from == $date_to) {
			$where['date_now'] = date_to_timestamp($date_from);
		} else {
			if(!empty($date_from)) {
				$where['date_from']	= date_to_timestamp($date_from);
			}
			if(!empty($date_to)) {
				$where['date_to'] = date_to_timestamp($date_to);
			}
		}

		$page_number     	= is_numeric($page_number) ? $page_number : 1;
		$data_per_page   	= 20;
		$arr_checking_log	= $this->mod_checking_log->get_results($where, $page_number, $data_per_page, $order_by);

		$this->load->config('pagination', TRUE);
		$conf_pg               = $this->config->item('pagination');
		$conf_pg['total_rows'] = $this->mod_checking_log->total_rows;
		$conf_pg['per_page']   = $data_per_page;
		$conf_pg['base_url']   = base_url($this->router->fetch_class() . '/' . $this->router->fetch_method());
		$this->pagination->initialize($conf_pg); // INIT

		$layout = array(
			'page'		    	=> 'pages/detection-logs',
			'title'		    	=> 'Detection Logs',
			'arr_checking_log'	=> $arr_checking_log
		);

		$this->layout->view($layout);
	}

	public function detail($request_id = false) {
		if (!$request_id || !is_uuid($request_id)) {
			show_404();
		}

		$arr_checking_log = $this->mod_checking_log->get_row(array('request_id' => $request_id));

		if (!$arr_checking_log) {
			show_404();
		}

		$layout = array(
			'page'		=> 'details/detection-log',
			'title'		=> 'Detail Detection Log',
			'arr_data'	=> $arr_checking_log
		);
		$this->layout->view($layout);
	}

	public function delete($checking_log_id = false) {
		if (!$checking_log_id || !is_uuid($checking_log_id)) {
			show_404();
		}

		$arr_checking_log = $this->mod_checking_log->get_row(array('checking_log_id' => $checking_log_id));

		if (!$arr_checking_log) {
			show_404();
		}

		$this->mod_checking_log->delete(array('checking_log_id' => $checking_log_id));
		$this->session->set_flashdata('success', 'Detection log has been deleted');
		
		redirect('detection_logs', 'refresh');
	}
}