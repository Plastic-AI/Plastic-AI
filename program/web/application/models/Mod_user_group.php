<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_user_group extends Titan_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'user_group';
	}

	public function get_results($where = array(), $page = 1, $show = 25, $order_val = 'ASC', $order_key = 'user_group_name') {
		$this->db->start_cache();
		$this->db->select('user_group_id, created, updated, created_by, updated_by, user_group_name, user_group_role, user_group_default, user_group_status');
		$this->db->from('user_group');
		$this->_filter($where);
		$this->db->stop_cache();
		$this->total_rows = $this->db->count_all_results();

		if (isset($filter['order_key'])) {
			$order_key = $filter['order_key'];
		}

		if (isset($filter['order_val'])) {
			$order_val = $filter['order_val'];
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
		$this->db->select('user_group_id, created, updated, created_by, updated_by, user_group_name, user_group_role, user_group_default, user_group_status');
		$this->db->from('user_group');

		
		$this->_filter($where);
		$this->db->limit(1);
		$query = $this->db->get();

		return $query->row();
	}

	private function _filter($where = array()) {
		if (!empty($where['user_group_id'])) {
			$this->db->where('user_group_id', $where['user_group_id']);
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

		if (!empty($where['user_group_default'])) {
			$this->db->where('user_group_default', $where['user_group_default']);
		}

		if (!empty($where['user_group_status'])) {
			$this->db->where('user_group_status', $where['user_group_status']);
		}

		if (!empty($where['user_group_name'])) {
			$this->db->like('LOWER(user_group_name)', strtolower($where['user_group_name']));
		}
	}
}