<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Titan_Controller extends CI_Controller {
	public $is_logged;
	public $user;
	public $user_group;
	public $role;
	public $user_id;
	public $user_login;

	public function __construct() {
		parent::__construct();

		$this->is_logged = $this->session->userdata('is_logged');

		if ($this->is_logged !== true) {
			redirect('login');
		}

		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
		$this->load->model('mod_change_log');
		$this->load->model('mod_user');
		$this->load->library('layout');

		$where = array(
			'user_login'        => $this->session->userdata('user_login'),
			'user_status'       => STATUS_ENABLE,
			'user_group_status' => STATUS_ENABLE
		);

		$arr_user = $this->mod_user->get_row($where);

		if (!$arr_user) {
			redirect('logout');
		}

		$this->user           = $arr_user;
		$this->role           = json_decode($this->user->user_group_role);
		$this->user_id		  = $this->user->user_id;
		$this->user_login     = $this->user->user_login;
		$this->controller     = $this->router->fetch_class();
		$this->action         = $this->router->fetch_method();
		$this->req_controller = 'req_' . $this->controller;

		$access_allowed = isset($this->role->{$this->controller}->{$this->action}) ? true : false;
		if (!$access_allowed && $this->controller !== 'dashboard') {
			show_404();
		}
	}

	public function access_roles() {
		$this->config->load('access_rules', TRUE);
		$roles = $this->config->item('access_rules');

		return json_decode(json_encode($roles));
	}

	public function ajax_output($data, $status, $callback = array()) {
		header('Content-Type: application/json');

		if (!$this->input->is_ajax_request()) {
			print json_encode(
				array(
					'data' => array(),
					'meta' => array(
						'status'     => 400,
						'callback'   => $callback,
						'csrf_token' => $this->security->get_csrf_hash()
					)
				)
			);
		} elseif (!isset($this->parent_controller) && !isset($this->action) && $method_allowed !== true) {
			print json_encode(
				array(
					'data' => array(),
					'meta' => array(
						'status'     => 403,
						'callback'   => $callback,
						'csrf_token' => $this->security->get_csrf_hash()
					)
				)
			);
		} else {
			print json_encode(
				array(
					'data' => $data,
					'meta' => array(
						'status'     => $status,
						'callback'   => $callback,
						'csrf_token' => $this->security->get_csrf_hash()
					)
				)
			);
		}
		exit;
	}
}

class Titan_Req_Controller extends CI_Controller {
	public $is_logged;
	public $user;
	public $user_group;
	public $role;
	public $user_id;
	public $user_login;

	public function __construct() {
		parent::__construct();
		$this->is_logged = $this->session->userdata('is_logged');

		if ($this->is_logged !== true) {
			show_json_error(403, array('location' => 'logout'));
		}

		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
		$this->load->model('mod_change_log');
		$this->load->model('mod_user');
		$this->load->library('layout');

		$where = array(
			'user_login'        => $this->session->userdata('user_login'),
			'user_status'       => STATUS_ENABLE,
			'user_group_status' => STATUS_ENABLE
		);

		$arr_user = $this->mod_user->get_row($where);

		if (!$arr_user) {
			show_json_error(403, array('location' => 'logout'));
		}

		$this->user              = $arr_user;
		$this->role              = json_decode($this->user->user_group_role);
		$this->user_id		  	 = $this->user->user_id;
		$this->user_login        = $this->user->user_login;
		$this->controller        = $this->router->fetch_class();
		$this->action            = $this->router->fetch_method();
		$this->parent_controller = ltrim($this->controller, 'req_');

		$general_request = array('req_log');

		$method_allowed = false;
		if (isset($this->role->{$this->parent_controller})) {
			foreach ($this->role->{$this->parent_controller} as $key => $value) {
				if (empty($this->base_role[$this->action])) {
					break;
				} else {
					if (in_array($key, $this->base_role[$this->action])) {
						$method_allowed = true;
						break;
					}
				}
			}
		}

		if (!isset($this->role->{$this->parent_controller}->{$this->action}) && !in_array($this->controller, $general_request) && $method_allowed !== true) {
			show_json_error(403, array('location' => 'dashboard'));
		}
	}

	public function ajax_output($data, $status, $callback = array()) {
		header('Content-Type: application/json');

		if (!$this->input->is_ajax_request()) {
			print json_encode(
				array(
					'data' => array(),
					'meta' => array(
						'status'     => 400,
						'callback'   => $callback,
						'csrf_token' => $this->security->get_csrf_hash()
					)
				)
			);
		} elseif (!isset($this->parent_controller) && !isset($this->action) && $method_allowed !== true) {
			print json_encode(
				array(
					'data' => array(),
					'meta' => array(
						'status'     => 403,
						'callback'   => $callback,
						'csrf_token' => $this->security->get_csrf_hash()
					)
				)
			);
		} else {
			print json_encode(
				array(
					'data' => $data,
					'meta' => array(
						'status'     => $status,
						'callback'   => $callback,
						'csrf_token' => $this->security->get_csrf_hash()
					)
				)
			);
		}
		exit;
	}
}

class Titan_Api_Controller extends CI_Controller {
	private $is_logged;
	private $user;
	private $user_group;

	public $role;
	public $user_login;

	public function __construct() {
		parent::__construct();

		$this->endpoint = $this->router->fetch_class();
		
		http_response_code(401);
		header('X-Powered-By: PlasticAI');
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-type: application/json');

		$header = $this->input->request_headers();
		if (empty($header['Authorization']) && empty($header['authorization'])) {
			$result = array(
				'meta' => array(
					'code'    => 401,
					'message' => 'Unauthorized, request header auth token and id needed!'
				)
			);
			print json_encode($result);
			die;
		}

		$this->load->model('mod_user');
		$this->load->helper('jwt_helper');

		$authorization 	= $header['Authorization'] ? $header['Authorization'] : $header['authorization'];
		$auth_token		= $authorization ? str_replace(array('Bearer', ' '), '', $authorization) : false;

		try {
			$jwt = jwt::decode($auth_token, $this->config->item('api_encryption_key'), true);
		} catch (\Exception $e) {
			$result = array(
				'meta' => array(
					'code'    => 401,
					'message' => 'Unauthorized, request header auth token has been expired!'
				)
			);
			print json_encode($result);
			die;
		}
		
		$auth_json		= json_decode($jwt);
		$exstract		= explode(".", $auth_json->_id);
		$auth_data		= jwt_security('decrypt', $exstract[1]);
		$arr_token_data = json_decode($auth_data);

		if (!$arr_token_data || time() > $arr_token_data->exp) {
			//$this->session->sess_destroy();
			$result = array(
				'meta' => array(
					'code'    => 401,
					'message' => 'Unauthorized, request header auth token has been expired!'
				)
			);
			print json_encode($result);
			die;
		}

		$this->user = $this->mod_user->auth($arr_token_data->login, $arr_token_data->passwd);
		if (!$this->user) {
			$result = array(
				'meta' => array(
					'code'    => 401,
					'message' => 'Wrong credential or temporarily disabled!'
				)
			);
			print json_encode($result);
			die;
		}

		$this->user_login = $this->user->user_login;
		$this->role       = json_decode($this->user->user_group_role);
	}
}