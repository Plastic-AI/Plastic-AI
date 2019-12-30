<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Titan_Form_validation extends CI_Form_validation {
	public $CI;
	public function __construct() {
		parent::__construct();

		$this->CI = &get_instance();
	}

	public function login_captcha_validate($value) {
		$last_captcha = $this->CI->session->userdata('login_captcha');
		$this->CI->session->unset_userdata('login_captcha');
		if ($last_captcha == $value) {
			return true;
		}

		$this->CI->form_validation->set_message('login_captcha_validate', 'Wrong captcha.');

		return false;
	}

	public function is_field_value_exist($value, $params) {
		$this->CI->form_validation->set_message('is_field_value_exist', 'Sorry, that %s not exist.');

		list($table, $field) = explode('.', $params);
		$query               = $this->CI->db->from($table)->where($field, $value)->limit(1)->get();
		if (!$query->row()) {
			return false;
		} else {
			return true;
		}
	}

	public function add_is_unique($value, $params) {
		$this->CI->load->database();
		$this->CI->form_validation->set_message('add_is_unique', 'Sorry, that %s is already being used.');
		list($table, $field) = explode('.', $params);

		$query = $this->CI->db->from($table)->where('LOWER(' . $field . ')', strtolower($value))->limit(1)->get();

		if ($query->row()) {
			return false;
		} else {
			return true;
		}
	}

	public function edit_is_unique($value, $params) {
		$this->CI->load->database();
		$this->CI->form_validation->set_message('edit_is_unique', 'Sorry, that %s is already being used.');
		list($table, $field, $field_id, $current_id) = explode('.', $params);

		$query = $this->CI->db->from($table)->where('LOWER(' . $field . ')', strtolower($value))->limit(1)->get()->row();

		if ($query && $query->{$field_id} != $current_id) {
			return false;
		} else {
			return true;
		}
	}

	public function decimal_numeric($value) {
		if (!is_numeric($value) || $value < 0) {
			$this->CI->form_validation->set_message('decimal_numeric', 'Amount format must be fill with numeric/decimal, example 10 or 9.81, negatif number did not allowed.');

			return false;
		}

		return true;
	}

	public function name_format($value) {
		if (!preg_match('/^[a-zA-Z0-9\-\. \-]*$/', $value)) {
			$this->CI->form_validation->set_message('name_format', 'Only A-Z, a-z, 0-9, space and dash allowed!');

			return false;
		}

		return true;
	}

	public function login_format($value) {
		if (!preg_match('/^[a-zA-Z0-9_]*$/', $value)) {
			$this->CI->form_validation->set_message('login_format', 'Only A-Z, a-z, 0-9, and underscore allowed!');

			return false;
		}

		return true;
	}
}