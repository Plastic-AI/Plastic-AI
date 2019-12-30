<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Req_user_group extends Titan_Req_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('mod_user_group');
	}

	public function add() {
		$this->form_validation->set_rules('txtGroupName', 'User Group Name', 'trim|required|name_format|max_length[150]|add_is_unique[user_group.user_group_name]');
		$this->form_validation->set_rules('cbStatus', 'User Group Status', 'trim|required|in_list[' . STATUS_ENABLE . ',' . STATUS_DISABLE . ']');
		$this->form_validation->set_rules('cbxGroupRoles', 'User Group Roles', 'trim');

		if ($this->form_validation->run() == FALSE) {
			$callback['fields'] = $this->form_validation->error_array();
			$this->ajax_output(array(), 400, $callback);
		} else {
			$_group_name  	= $this->input->post('txtGroupName', TRUE);
			$_status      	= $this->input->post('cbStatus', TRUE);
			$_role_groups 	= $this->input->post('cbxGroupRoles', TRUE);
			$_default_group = $this->input->post('cbDefaultGroup') == IS_DEFAULT_GROUP_ID ? IS_DEFAULT_GROUP_ID : NOT_DEFAULT_GROUP_ID;

			$arr_fields = array(
				'user_group_name'   	=> $_group_name,
				'user_group_role'   	=> json_encode($_role_groups, TRUE),
				'user_group_default'	=> $_default_group,
				'user_group_status' 	=> $_status
			);

			$insert = $this->mod_user_group->insert($arr_fields);
			if (!$insert) {
				$callback['noty'] = array('type' => 'error', 'text' => 'Query failed');
				$this->ajax_output('', 503, $callback);
			} else {
				$this->session->set_flashdata('success', 'User group has been added');
				if (isset($this->role->{$this->parent_controller})) {
					$callback['location'] = 'user_group';
				} else {
					$callback['location'] = 'dashboard';
				}

				$this->ajax_output('', 200, $callback);
			}
		}
	}

	public function update($user_group_id = false) {
		if (!$user_group_id) {
			$this->session->set_flashdata('warning', 'The page you trying to access did not exist!');
			$this->ajax_output(array(), 400, array('location' => 'user_group'));
		}

		$arr_user_group = $this->mod_user_group->get_row(array('user_group_id' => $user_group_id));
		if (!$arr_user_group) {
			$this->session->set_flashdata('warning', 'The page you trying to access did not exist!');
			$this->ajax_output(array(), 400, array('location' => 'user_group'));
		}

		$this->form_validation->set_rules('txtGroupName', 'User Group Name', 'trim|required|name_format|max_length[150]|edit_is_unique[user_group.user_group_name.user_group_id.' . $user_group_id . ']');
		$this->form_validation->set_rules('cbStatus', 'User Group Status', 'trim|required|in_list[' . STATUS_ENABLE . ',' . STATUS_DISABLE . ']');
		$this->form_validation->set_rules('cbxGroupRoles', 'User Group Roles', 'trim');

		if ($this->form_validation->run() == FALSE) {
			$callback['fields'] = $this->form_validation->error_array();
			$this->ajax_output(array(), 400, $callback);
		} else {
			$_group_name  	= $this->input->post('txtGroupName', TRUE);
			$_status      	= $this->input->post('cbStatus', TRUE);
			$_role_groups 	= $this->input->post('cbxGroupRoles', TRUE);
			$_default_group = $this->input->post('cbDefaultGroup') == IS_DEFAULT_GROUP_ID ? IS_DEFAULT_GROUP_ID : NOT_DEFAULT_GROUP_ID;

			$arr_fields = array(
				'user_group_name'   	=> $_group_name,
				'user_group_role'   	=> json_encode($_role_groups, TRUE),
				'user_group_default'	=> $_default_group,
				'user_group_status' 	=> $_status
			);

			$update = $this->mod_user_group->update($arr_fields, array('user_group_id' => $arr_user_group->user_group_id));

			if (!$update) {
				$callback['noty'] = array('type' => 'error', 'text' => 'Query failed');
			} else {
				$callback['noty'] = array('type' => 'success', 'text' => 'Updated');
			}

			$this->ajax_output('', 200, $callback);
		}
	}
}