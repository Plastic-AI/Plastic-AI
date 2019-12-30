<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detection_logs extends Titan_Api_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('mod_api');
		$this->load->model('mod_checking_log');
    }

	public function index() {
		$this->mod_api->method('GET');

		$keyword		= $this->input->get('keyword');
		$page_number	= $this->input->get('page') ? $this->input->get('page') : 1;
		$sort_by		= $this->input->get('sort_by') ? $this->input->get('sort_by') : 'id';
		$order			= $this->input->get('order') ? $this->input->get('order') : 'ASC';

		$this->form_validation->set_rules('keyword', 'keyword', 'trim|name_format|max_length[150]');
		$this->form_validation->set_rules('page', 'page', 'trim|numeric');
		$this->form_validation->set_rules('sort_by', 'sort_by', 'trim|in_list[id,name]');
		$this->form_validation->set_rules('order', 'order', 'trim|in_list[asc,desc,ASC,DESC]');

		if ( $this->form_validation->run() == FALSE ) {
			show_api_error(400, $this->form_validation->error_array());
			return;
		}

		$where['search_keyword'] = $keyword;
		if($sort_by == 'id') $key = 'created';
		if($sort_by == 'name') $key = 'plastic_type';

		$page_number     = is_numeric($page_number) ? $page_number : 1;
		$data_per_page   = 20;
		$arr_question 	 = $this->mod_checking_log->get_results($where, $page_number, $data_per_page, $order, $key);

		$this->load->config('pagination', TRUE);
		$conf_pg               = $this->config->item('pagination');
		$conf_pg['total_rows'] = $this->mod_checking_log->total_rows;
		$conf_pg['per_page']   = $data_per_page;
		$conf_pg['base_url']   = current_url() . (!empty($this->input->server('QUERY_STRING')) ? '?' . $this->input->server('QUERY_STRING') : '');
		$this->pagination->initialize($conf_pg);

		if(!$arr_question) {
			show_api_error(404, array('No data found.'));
			return;
		} else {
			foreach ($arr_question as $value) {
				$data[] = array(
                    'request_id'	=> $value->request_id,
                    'type'			=> $value->plastic_type,
                    'percentage'	=> $value->plastic_percentage,
                    'file'			=> base_url($value->plastic_file),
                    'time_used'		=> $value->time_used,
                    'status'		=> $value->plastic_status
				);
			}
			
			show_api_data(200, $data, array('OK'), array(
				'current_page'	=> $page_number,
				'total_data'	=> $conf_pg['total_rows'],
				'item_per_page' => $data_per_page,
				'links'			=> array(
						$conf_pg['base_url']
					)
				)
			);
			return;
		}
    }

	public function detail($request_id = false) {
		$this->mod_api->method('GET');
		$this->mod_api->validate_id('checking_logs', 'request_id', $request_id);

        $value = $this->mod_checking_log->get_row(array('request_id' => $request_id));
	
		$data = array(
            'request_id'		=> $value->request_id,
            'type'				=> $value->plastic_type,
            'percentage'		=> $value->plastic_percentage,
            'file'				=> base_url($value->plastic_file),
            'time_used'			=> $value->time_used,
            'brain_response'	=> json_decode($value->request_data),
            'status'			=> $value->plastic_status
		);

		show_api_data(200, $data, array('OK'));
		return;
	}

	public function delete($request_id = false) {
		$this->mod_api->method('GET');
		$this->mod_api->validate_id('checking_logs', 'request_id', $request_id);

        $delete = $this->mod_checking_log->delete(array('request_id' => $request_id));
        if($delete) {
        	$data = array(
        		'request_id' => $request_id,
        		'deleted'	 => true,
        	);
        } else {
        	$data = array(
        		'request_id' => $request_id,
        		'deleted'	 => false,
        	);
        }

		show_api_data(200, $data, array('OK'));
		return;
	}

}