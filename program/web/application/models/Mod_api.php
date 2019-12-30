<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_api extends Titan_Model {
	public function __construct() {
		parent::__construct();
    }

    public function method($method = false) {
        if ( $this->input->server('REQUEST_METHOD') != $method ) {
			show_api_error(405, array('Method Not Allowed, ' . strtoupper($method) . ' Only'));
			exit(4);
        }
    }

    public function validate_id($table = false, $field = false, $id = false) {
        if (!$id) {
			show_api_error(405, array('Parameter id needed.'));
			exit(4);
        }

        if (!is_string($id) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $id) !== 1)) {
            show_api_error(405, array('Invalid parameter id.'));
			exit(4);
        }
        
        $this->CI->db->from($table);
		$this->CI->db->where($field, $id);
        $query = $this->CI->db->limit(1)->get();
		if (!$query->row()) {
            show_api_error(400, array(ucfirst(str_replace(array('_', ' id'), array(' ', ''), $field)) . ' did not exist.'));
            exit(4);
		}
    }
}