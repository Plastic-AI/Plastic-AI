<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Req_user extends Titan_Req_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('mod_user');
	}

	public function add() {
		$this->form_validation->set_rules('txtNewLogin', 'Login', 'trim|required|login_format|min_length[1]|max_length[32]|is_unique[users.user_login]');
		$this->form_validation->set_rules('txtEmailLogin', 'User Email', 'trim|required|valid_email|min_length[13]|max_length[32]|is_unique[users.user_email]');
		$this->form_validation->set_rules('cbUserGroup', 'User Group', 'trim|required');
		$this->form_validation->set_rules('txtFullname', 'Full Name', 'trim|required|name_format|min_length[1]|max_length[150]');
		$this->form_validation->set_rules('cbStatus', 'Status', 'trim|required|in_list[' . STATUS_ENABLE . ',' . STATUS_DISABLE . ']');
		$this->form_validation->set_rules('txtNewPassword', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('txtConfirmNewPassword', 'Password', 'trim|required|min_length[6]|matches[txtNewPassword]');
		$this->form_validation->set_rules('cbApiAccessOnly', 'API Access Only', 'trim|numeric|in_list[' . STATUS_ENABLE . ',' . STATUS_DISABLE . ']');
		$this->form_validation->set_rules('txtBalance', 'Balance', 'trim|numeric');

		if ($this->form_validation->run() == FALSE) {
			$callback['fields'] = $this->form_validation->error_array();
			$this->ajax_output(array(), 400, $callback);
		} else {
			$arr_fields = array(
				'user_name'     	=> $this->input->post('txtFullname', TRUE),
				'user_login'    	=> strtolower($this->input->post('txtNewLogin', TRUE)),
				'user_email'    	=> strtolower($this->input->post('txtEmailLogin', TRUE)),
				'user_password'  	=> sha1($this->input->post('txtNewPassword')),
				'user_group_id'  	=> $this->input->post('cbUserGroup', TRUE),
				'user_balance'		=> $this->input->post('txtBalance', TRUE) ? $this->input->post('txtBalance', TRUE) : NULL,
				'user_status'    	=> $this->input->post('cbStatus', TRUE),
				'api_access_only' 	=> $this->input->post('cbApiAccessOnly') == STATUS_ENABLE ? STATUS_ENABLE : STATUS_DISABLE
			);

			$insert = $this->mod_user->insert($arr_fields);
			$this->session->set_flashdata('success', 'User has been added');
			if (isset($this->role->{$this->parent_controller})) {
				$callback['location'] = 'user';
			} else {
				$callback['location'] = 'dashboard';
			}

			$this->ajax_output('', 200, $callback);
		}
	}

	public function update($user_id = false) {
		if (!$user_id) {
			$this->ajax_output(array(), 400, array('location' => 'user'));
		}

		$this->action_id = $user_id;
		$arr_user = $this->mod_user->get_row(array('user_id' => $user_id));

		if (!$arr_user) {
			$this->ajax_output(array(), 400, array('location' => 'user'));
		}

		$this->form_validation->set_rules('cbUserGroup', 'User Group', 'trim|required');
		$this->form_validation->set_rules('txtFullname', 'Fullname', 'trim|required|max_length[150]|name_format');
		$this->form_validation->set_rules('txtEmailLogin', 'User Email', 'trim|required|valid_email|min_length[13]|max_length[32]|edit_is_unique[users.user_email.user_id.' . $user_id . ']');
		$this->form_validation->set_rules('cbStatus', 'Status', 'trim|required|in_list[' . STATUS_ENABLE . ',' . STATUS_DISABLE . ']');
		$this->form_validation->set_rules('cbApiAccessOnly', 'API Access Only', 'trim|numeric|in_list[' . STATUS_ENABLE . ',' . STATUS_DISABLE . ']');
		$this->form_validation->set_rules('txtBalance', 'Balance', 'trim|numeric');

		if ($this->form_validation->run() == FALSE) {
			$callback['fields'] = $this->form_validation->error_array();
			$this->ajax_output(array(), 400, $callback);
		} else {
			$_group_id        = $this->input->post('cbUserGroup', TRUE);
			$_fullname        = $this->input->post('txtFullname', TRUE);
			$_email		      = $this->input->post('txtEmailLogin', TRUE);
			$_balance		  = $this->input->post('txtBalance', TRUE) ? $this->input->post('txtBalance', TRUE) : NULL;
			$_status          = $this->input->post('cbStatus', TRUE);
			$_api_access_only = $this->input->post('cbApiAccessOnly') == STATUS_ENABLE ? STATUS_ENABLE : STATUS_DISABLE;

			$arr_fields = array(
				'user_group_id'  	=> $_group_id,
				'user_name'      	=> $_fullname,
				'user_email'	  	=> $_email,
				'user_status'    	=> $_status,
				'api_access_only' 	=> $_api_access_only,
				'user_balance'		=> $_balance,
			);

			$update            = $this->mod_user->update($arr_fields, array('user_id' => $arr_user->user_id));
			$callback['query'] = $this->db->last_query();
			if (!$update) {
				$callback['noty'] = array('type' => 'error', 'text' => 'Query failed');
			} else {
				$callback['noty'] = array('type' => 'success', 'text' => 'Updated');
			}

			$this->ajax_output('', 200, $callback);
		}
	}

	public function change_password($user_id = false) {
		if (!$user_id) {
			$callback['location'] = 'user';
			$this->ajax_output(array(), 400, $callback);
		}

		$this->action_id = $user_id;
		$arr_user = $this->mod_user->get_row(array('user_id' => $user_id));

		if (!$arr_user) {
			$callback['location'] = 'user';
			$this->ajax_output(array(), 400, $callback);
		}

		$this->form_validation->set_rules('txtNewPassword', 'New Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('txtNewPasswordVerify', 'Confirm New Password', 'trim|required|matches[txtNewPassword]');

		if ($this->form_validation->run() == FALSE) {
			$callback['fields'] = $this->form_validation->error_array();
			$this->ajax_output(array(), 400, $callback);
		} else {
			$_password = $this->input->post('txtNewPassword');
			$update    = $this->mod_user->update_password($user_id, $_password);
			if (!$update) {
				$callback['noty'] = array('type' => 'error', 'text' => 'Query failed');
			} else {
				$callback['noty'] = array('type' => 'success', 'text' => 'Password Changed');
			}

			$this->ajax_output('', 200, $callback);
		}
	}
}