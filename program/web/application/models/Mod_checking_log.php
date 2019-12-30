<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_checking_log extends Titan_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'checking_logs';
	}

	public function get_results($where = array(), $page = 1, $show = 25, $order_val = 'DESC', $order_key = 'created') {
		$this->db->start_cache();
		$this->db->select('checking_log_id, created, updated, created_by, updated_by, plastic_type, plastic_percentage, plastic_file, time_used, request_id, request_data, plastic_status');
		$this->db->from($this->table_name);
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
		$this->db->select('checking_log_id, created, updated, created_by, updated_by, plastic_type, plastic_percentage, plastic_file, time_used, request_id, request_data, plastic_status');
		$this->db->from($this->table_name);

		$this->_filter($where);
		$this->db->limit(1);
		$query = $this->db->get();

		return $query->row();
	}

	private function _filter($where = array()) {
		if (!empty($where['checking_log_id'])) {
			$this->db->where('checking_log_id', $where['checking_log_id']);
		}

		if (!empty($where['created'])) {
			$this->db->where('created', $where['created']);
		}

		if (!empty($where['updated'])) {
			$this->db->where('updated', $where['updated']);
		}

		if (isset($where['date_from']) && !empty($where['date_from'])) {
			$this->db->where('created >= ', $where['date_from']);
		}

		if (isset($where['date_to']) && !empty($where['date_to'])) {
			$this->db->where('created <= ', $where['date_to']);
		}

		if (!empty($where['created_by'])) {
			$this->db->where('created_by', $where['created_by']);
		}

		if (!empty($where['updated_by'])) {
			$this->db->where('updated_by', $where['updated_by']);
		}

		if (!empty($where['plastic_type'])) {
			$this->db->like('LOWER(plastic_type)', strtolower($where['plastic_type']));
		}

		if (!empty($where['plastic_percentage'])) {
			$this->db->where('plastic_percentage', $where['plastic_percentage']);
		}

		if (!empty($where['plastic_file'])) {
			$this->db->where('plastic_file', $where['plastic_file']);
		}

		if (!empty($where['time_used'])) {
			$this->db->where('time_used', $where['time_used']);
		}

		if (!empty($where['request_id'])) {
			$this->db->where('request_id', $where['request_id']);
		}

		if (!empty($where['request_data'])) {
			$this->db->where('request_data', $where['request_data']);
		}

		if (!empty($where['plastic_status'])) {
			$this->db->where('plastic_status', $where['plastic_status']);
		}
	}
}