<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_change_log extends Titan_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'change_logs';
	}

	public function get_lastes_action_logs($controller, $action, $segment = false) {
		$where['controller'] = $controller;
		$where['action']     = $action;

		if ($segment) {
			$where['url_like_before'] = '/' . $controller . '/' . $action . '/' . $segment;
		}

		return $this->get_results($where, 1, 5);
	}

	public function total_data() {
		$this->db->select('log_id');
		$this->db->from('change_logs');

		return $this->db->count_all_results();
	}

	public function get_results($where = array(), $page = 1, $show = 25, $order_val = 'DESC', $order_key = 'log_id') {
		$this->db->start_cache();
		$this->db->select('log_id, created, created_by, controller, action, querystring, post, url, ip, current_data, new_data, db_query, browser, method');
		$this->db->from('change_logs');

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

		$offset = ($page - 1) * $show;
		$this->db->limit($show, $offset);

		$query = $this->db->get();

		$this->db->flush_cache();

		return $query->result();
	}

	public function get_row($where = array()) {
		$this->db->select('log_id, created, created_by, controller, action, querystring, post, url, ip, current_data, new_data, db_query, browser, method');
		$this->db->from('change_logs');

		$this->where($where);

		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	private function where($where) {
		if (!empty($where['log_id'])) {
			$this->db->where('log_id', $where['admin_id']);
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

		if (!empty($where['controller'])) {
			$this->db->where('controller', $where['controller']);
		}

		if (!empty($where['action'])) {
			$this->db->where('action', $where['action']);
		}

		if (!empty($where['url'])) {
			$this->db->where('url', $where['url']);
		}

		if (!empty($where['url_like'])) {
			$this->db->like('url', $where['url_like']);
		}

		if (!empty($where['ip'])) {
			$this->db->where('ip', $where['ip']);
		}

		if (!empty($where['method'])) {
			$this->db->where('method', $where['method']);
		}
	}
}