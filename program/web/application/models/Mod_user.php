<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_user extends Titan_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'users';
		$this->load->library('email');
	}

	public function auth($login, $password) {
		$where = array(
			'user_login'        => $login,
			'user_password'     => sha1($password),
			'user_status'       => STATUS_ENABLE,
			'api_access_only'    => STATUS_DISABLE,
			'user_group_status' => STATUS_ENABLE
		);

		$user = $this->get_row($where);

		if ($user) {
			$fields = array(
				'user_last_login'   => time(),
				'user_last_ip'      => $this->input->ip_address(),
				'user_last_browser' => $this->input->user_agent()
			);

			$this->db->update($this->table_name, $fields, array('user_id' => $user->user_id));

			return $user;
		}

		return FALSE;
	}

	public function forgot_password($email) {
		$where = array(
			'user_email' 		=> $email,
			'user_status'       => STATUS_ENABLE,
			'api_access_only'   => STATUS_DISABLE,
			'user_group_status' => STATUS_ENABLE
		);

		$user = $this->get_row($where);
		$forgot_password_token = mb_strtoupper(strval(bin2hex(openssl_random_pseudo_bytes(16))));

		if ($user) {
			$fields = array(
				'forgot_password_token' => $forgot_password_token
			);

			$this->db->update($this->table_name, $fields, array('user_id' => $user->user_id));

			$this->email->from('no-reply@blackportal.io', 'blackportal');
			$this->email->to($email);
			$this->email->subject('Confirm Forgot Password');
			$this->email->message('Change your password: ' . base_url('reset_password/' . $forgot_password_token));
			$this->email->send();

			return $user;
		}

		return FALSE;
	}

	public function email($email) {
		$where = array(
			'user_email'        => $email,
			'user_status'       => STATUS_ENABLE,
			'api_access_only'   => STATUS_DISABLE,
			'user_group_status' => STATUS_ENABLE
		);

		$user = $this->get_row($where);

		if ($user) {
			$fields = array(
				'user_last_login'   => time(),
				'user_last_ip'      => $this->input->ip_address(),
				'user_last_browser' => $this->input->user_agent()
			);

			$this->db->update($this->table_name, $fields, array('user_id' => $user->user_id));

			return $user;
		}

		return false;
	}

	public function update_secret($user_id, $secret) {
		$fields = array(
			'user_secret' => $secret
		);

		return $this->db->update($this->table_name, $fields, array('user_id' => $user_id));
	}

	public function update_password($user_id, $password) {
		$fields['user_password'] = sha1($password);
		$fields['forgot_password_token'] = NULL;

		return $this->update($fields, array('user_id' => $user_id));
	}

	public function get_results($where = array(), $page = 1, $show = 25, $order_val = 'DESC', $order_key = 'a.created') {
		$this->db->start_cache();
		$this->db->select('a.user_id, a.created, a.updated, a.created_by, a.updated_by, a.user_name, a.user_login, a.user_email, a.user_password, a.user_last_login, a.user_last_ip, a.user_last_browser, a.user_group_id, a.user_status, a.user_secret, a.api_access_only, a.forgot_password_token, g.user_group_name, g.user_group_role, g.user_group_default, g.user_group_status');
		$this->db->from('users a');
		$this->db->join('user_group g', 'a.user_group_id = g.user_group_id');

		$this->where($where);

		$this->db->stop_cache();
		$this->total_rows = $this->db->count_all_results();

		if (isset($where['order_key'])) {
			$order_key = $where['order_key'];
		}

		if (isset($where['order_val'])) {
			$order_val = $where['order_val'];
		}
		$this->db->order_by($order_key, $order_val);

		if ($page != 'all') {
			$offset = ($page - 1) * $show;
			$this->db->limit($show, $offset);
		} else {
			$this->db->limit(9999);
		}

		$query = $this->db->get();

		$this->db->flush_cache();

		return $query->result();
	}

	public function get_row($where = array()) {
		$this->db->select('a.user_id, a.created, a.updated, a.created_by, a.updated_by, a.user_name, a.user_login, a.user_email, a.user_password, a.user_last_login, a.user_last_ip, a.user_last_browser, a.user_group_id, a.user_status, a.user_secret, a.api_access_only, a.forgot_password_token, g.user_group_name, g.user_group_role, g.user_group_default, g.user_group_status');
		$this->db->from('users a');
		$this->db->join('user_group g', 'a.user_group_id = g.user_group_id');

		$this->where($where);
		$this->db->limit(1);
		$query = $this->db->get();

		return $query->row();
	}

	private function where($where) {
		if (isset($where['user_id'])) {
			$this->db->where('a.user_id', $where['user_id']);
		}

		if (!empty($where['created'])) {
			$this->db->where('created', $where['created']);
		}

		if (!empty($where['updated'])) {
			$this->db->where('updated', $where['updated']);
		}

		if (!empty($where['created_by'])) {
			$this->db->where('created_by', $where['created_by']);
		}

		if (!empty($where['updated_by'])) {
			$this->db->where('updated_by', $where['updated_by']);
		}

		if (isset($where['user_name'])) {
			$this->db->where('a.user_name', $where['user_name']);
		}

		if (isset($where['user_name_like'])) {
			$this->db->like('a.user_name', $where['user_name_like']);
		}

		if (isset($where['user_login'])) {
			$this->db->where('a.user_login', $where['user_login']);
		}

		if (isset($where['user_email'])) {
			$this->db->where('a.user_email', $where['user_email']);
		}

		if (isset($where['user_password'])) {
			$this->db->where('a.user_password', $where['user_password']);
		}

		if (isset($where['user_ip'])) {
			$this->db->where('a.user_ip', $where['user_ip']);
		}

		if (isset($where['user_status'])) {
			$this->db->where('a.user_status', $where['user_status']);
		}

		if (isset($where['api_access_only'])) {
			$this->db->where('a.api_access_only', $where['api_access_only']);
		}

		if (isset($where['forgot_password_token'])) {
			$this->db->where('a.forgot_password_token', $where['forgot_password_token']);
		}

		if (isset($where['user_group_id'])) {
			$this->db->where('g.user_group_id', $where['user_group_id']);
		}

		if (isset($where['user_group_name'])) {
			$this->db->where('g.user_group_name', $where['user_group_name']);
		}

		if (isset($where['user_group_default'])) {
			$this->db->where('g.user_group_default', $where['user_group_default']);
		}

		if (isset($where['user_group_status'])) {
			$this->db->where('g.user_group_status', $where['user_group_status']);
		}
	}
}