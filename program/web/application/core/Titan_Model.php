<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Titan_Model extends CI_Model {
	public $CI;
	public $user_login;

	public function __construct() {
		parent::__construct();

		$this->CI = &get_instance();
	}

	public function insert($fields, $created_by = NULL) {
		if (!$this->table_name) {
			return false;
		}

		$fields['created'] = time();
		if($this->table_name != 'faces' && $this->table_name != 'request_logs' && $this->table_name != 'streams') {
			$fields['updated'] = time();
		}

		if($created_by == NULL) {
			$fields['created_by'] = $this->CI->user_login;
			if($this->table_name != 'faces' && $this->table_name != 'request_logs' && $this->table_name != 'streams') {
				$fields['updated_by'] = $this->CI->user_login;
			}
		} else {
			$fields['created_by'] = $created_by;
			if($this->table_name != 'faces' && $this->table_name != 'request_logs' && $this->table_name != 'streams') {
				$fields['updated_by'] = $created_by;
			}
		}

		$insert = $this->db->insert($this->table_name, $fields);
		if (!$insert) {
			return false;
		}

		$runing_query	= $this->db->last_query();

		// Loging Insert Query
		if(!empty($this->CI->user_login)) {
			$this->changelog($runing_query, $fields);
		}

		return $insert;
	}

	public function update($fields, $where, $created_by = NULL) {
		if (!$this->table_name) {
			return false;
		}

		// Get current data for logging
		$this->db->where($where);
		$query        = $this->db->get($this->table_name);
		$current_data = $query->row();

		$fields['updated'] = time();

		#$fields['updated_by']	= $this->session->userdata( 'user_login' );
		if($this->table_name == 'users' && $created_by != NULL) {
			$fields['updated_by'] = $created_by;
		} else {
			$fields['updated_by'] = $this->CI->user_login;
		}

		$update = $this->db->update($this->table_name, $fields, $where);

		if (!$update) {
			return false;
		}

		$runing_query = $this->db->last_query();

		// Loging Insert Query
		$this->changelog($runing_query, $fields, $current_data);

		return $update;
	}

	public function delete($where) {
		if (!$this->table_name) {
			return false;
		}

		// Get current data for logging
		$this->db->where($where);
		$query        = $this->db->get($this->table_name);
		$current_data = $query->row();

		$fields['updated'] = time();

		#$fields['updated_by']	= $this->session->userdata( 'user_login' );
		$fields['updated_by'] = $this->CI->user_login;

		$delete = $this->db->delete($this->table_name, $where);
		if (!$delete) {
			return false;
		}

		$runing_query = $this->db->last_query();

		// Loging Insert Query
		$this->changelog($runing_query, $fields, $current_data);

		return $delete;
	}
	
	public function usagelog($user_id, $service_id, $cost) {
		$login_as 	= $this->CI->user_login;

		$insert = array(
			'created'      	=> time(),
			'created_by'   	=> $login_as,
			'user_id'		=> $user_id,
			'service_id'  	=> $service_id,
			'cost'         	=> $cost
		);

		$this->db->insert('usages', $insert);
	}
	

	public function changelog($db_query, $new_data = array(), $current_data = array()) {
		$login_as 	= $this->CI->user_login;
		$controller = $this->CI->router->fetch_class();
		$method     = $this->CI->router->fetch_method();
		$post       = $this->CI->input->post();
		$url        = current_url() . (!empty($this->input->server('QUERY_STRING')) ? '?' . $this->input->server('QUERY_STRING') : '');
		$ip         = $this->input->ip_address();

		$insert = array(
			'created'      => time(),
			'created_by'   => $login_as,
			'controller'   => $controller,
			'action'       => $method,
			'querystring'  => $db_query,
			'post'         => json_encode($post),
			'url'          => $url,
			'ip'           => $ip,
			'current_data' => json_encode($current_data),
			'new_data'     => json_encode($new_data)
		);

		$this->db->insert('change_logs', $insert);
	}
}