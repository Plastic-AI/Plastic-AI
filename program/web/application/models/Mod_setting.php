<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_setting extends Titan_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'settings';
	}

	public function get_row($filter = array()) {
		$this->db->select('created, updated, created_by, updated_by, telegram_api, telegram_chatid, notification_detail, notification_status');
		$this->db->from($this->table_name);
		$this->_filter($filter);
		$this->db->limit(1);
		$query = $this->db->get();

		return $query->row();
	}

	private function _filter($where = array()) {
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

		if (!empty($where['telegram_api'])) {
			$this->db->where('telegram_api', $where['telegram_api']);
		}

		if (!empty($where['telegram_chatid'])) {
			$this->db->where('telegram_chatid', $where['telegram_chatid']);
		}

		if (!empty($where['notification_detail'])) {
			$this->db->where('notification_detail', $where['notification_detail']);
		}

		if (!empty($where['notification_status'])) {
			$this->db->where('notification_status', $where['notification_status']);
		}
	}
}