<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_group extends Titan_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model('mod_user_group');
	}

	public function index($page_number = 1) {
		$user_group_status = is_numeric($this->input->get('cbStatus')) ? $this->input->get('cbStatus') : false;
		$user_group_name   = $this->input->get('txtName');

		$where['user_group_status'] = $user_group_status;
		$where['user_group_name']   = $user_group_name;

		$page_number     = is_numeric($page_number) ? $page_number : 1;
		$data_per_page   = 20;
		$arr_user_group	 = $this->mod_user_group->get_results($where, $page_number, $data_per_page);

		$this->load->config('pagination', TRUE);
		$conf_pg               = $this->config->item('pagination');
		$conf_pg['total_rows'] = $this->mod_user_group->total_rows;
		$conf_pg['per_page']   = $data_per_page;
		$conf_pg['base_url']   = base_url($this->router->fetch_class() . '/' . $this->router->fetch_method());
		$this->pagination->initialize($conf_pg); // INIT

		$layout = array(
			'page'				=> 'pages/user-group',
			'title'				=> 'User Group',
			'arr_user_group'	=> $arr_user_group
		);

		$this->layout->view($layout);
	}

	public function add() {
		$arr_layout = array(
			'page'               => 'forms/user-group-add',
			'title'              => 'Add User Group',
			'arr_available_role' => $this->access_roles()
		);
		$this->layout->view($arr_layout);
	}

	public function update($user_group_id = FALSE) {
		if (!$user_group_id || !is_uuid($user_group_id)) {
			$this->session->set_flashdata('warning', 'The page you trying to access did not exist!');
			redirect('user_group');
		}

		$arr_user_group = $this->mod_user_group->get_row(array('user_group_id' => $user_group_id));
		if (!$arr_user_group) {
			$this->session->set_flashdata('warning', 'The page you trying to access did not exist!');
			redirect('user_group');
		}

		$arr_role   = json_decode($arr_user_group->user_group_role);

		$arr_layout = array(
			'page'               => 'forms/user-group-update',
			'title'              => 'Update User Group',
			'arr_user_group'     => $arr_user_group,
			'arr_role'           => $arr_role,
			'arr_available_role' => $this->access_roles(),
			'arr_change_logs'    => $this->mod_change_log->get_lastes_action_logs($this->controller, $this->action, $user_group_id)
		);

		$this->layout->view($arr_layout);
	}
}