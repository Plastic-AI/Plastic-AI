<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Titan_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('mod_user');
		$this->load->model('mod_user_group');
	}

	public function index($page_number = 1) {
		$where = array();
		if ($this->input->get('cbStatus')) {
			$where['user_status'] = is_numeric($this->input->get('cbStatus')) ? $this->input->get('cbStatus') : '';
		}

		if ($this->input->get('txtLogin')) {
			$where['user_login'] = $this->input->get('txtLogin');
		}

		if ($this->input->get('txtName')) {
			$where['user_name_like'] = $this->input->get('txtName');
		}

		$page_number   = is_numeric($page_number) ? $page_number : 1;
		$data_per_page = 20;
		$arr_users    = $this->mod_user->get_results($where, $page_number, $data_per_page);

		$this->load->config('pagination', TRUE);
		$conf_pg               = $this->config->item('pagination');
		$conf_pg['total_rows'] = $this->mod_user->total_rows;
		$conf_pg['per_page']   = $data_per_page;
		$conf_pg['base_url']   = base_url($this->router->fetch_class() . '/' . $this->router->fetch_method());
		$this->pagination->initialize($conf_pg);

		$layout = array(
			'page'			=> 'pages/user',
			'title'			=> 'User',
			'arr_users'		=> $arr_users
		);

		$this->layout->view($layout);
	}

	public function add() {
		$layout = array(
			'page'            => 'forms/user-add',
			'title'           => 'Add User',
			'arr_user_group'  => $this->mod_user_group->get_results(array('user_status' => STATUS_ENABLE), 'all'),
			'arr_change_logs' => $this->mod_change_log->get_lastes_action_logs($this->controller, $this->action)
		);
		$this->layout->view($layout);
	}

	public function update($user_id = false) {
		if (!$user_id || !is_uuid($user_id)) {
			show_404();
		}

		$this->action_id = $user_id;
		$arr_user        = $this->mod_user->get_row(array('user_id' => $user_id));

		if (!$arr_user) {
			show_404();
		}

		$arr_layout = array(
			'page'           => 'forms/user-update',
			'title'          => 'Update User',
			'arr_user'       => $arr_user,
			'arr_user_group' => $this->mod_user_group->get_results(array('user_status' => STATUS_ENABLE))
		);

		$this->layout->view($arr_layout);
	}

	public function change_password($user_id = false) {
		if (!$user_id) {
			$title = 'Change Password';
			$arr_user = $this->mod_user->get_row(array('user_id' => $this->user->user_id));
		} else {
			if (!is_uuid($user_id)) {
				show_404();
			}

			$title = 'Change User Password';
			$arr_user = $this->mod_user->get_row(array('user_id' => $user_id));
		}

		if (!$arr_user) {
			show_404();
		}

		$arr_layout = array(
			'page'		=> 'layouts/change-password',
			'title'     => $title,
			'arr_user'	=> $arr_user
		);

		$this->layout->view($arr_layout);
	}

	public function authenticate($user_id = false) {
		if (!$user_id || !is_uuid($user_id)) {
			show_404();
		}

		$arr_user = $this->mod_user->get_row(array('user_id' => $user_id));

		if (!$arr_user) {
			show_404();
		}

		$update = $this->mod_user->update(array('user_secret' => NULL), array('user_id' => $arr_user->user_id));
		if($update) {
			$this->session->set_flashdata('success', 'Authenticate has been reset successfully');
		} else {
			$this->session->set_flashdata('error', 'Query failed');
		}
		redirect('user/index', 'refresh');
	}

	public function terminate($user_id = false) {
		if (!$user_id || !is_uuid($user_id)) {
			show_404();
		}

		$arr_user = $this->mod_user->get_row(array('user_id' => $user_id));

		if (!$arr_user) {
			show_404();
		}

		$update = $this->mod_user->update(array('user_status' => 3), array('user_id' => $arr_user->user_id));
		if($update) {
			$this->session->set_flashdata('success', 'Account has been terminate');
		} else {
			$this->session->set_flashdata('error', 'Query failed');
		}
		redirect('user/index', 'refresh');
	}
}