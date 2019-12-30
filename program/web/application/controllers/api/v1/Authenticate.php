<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticate extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

		if ( $this->input->server('REQUEST_METHOD') != 'POST' ) {
			show_api_error(405, array('Method Not Allowed, POST Only'));
			return;
		}

		$login 		= $this->input->post( 'login' );
		$password 	= $this->input->post( 'password' );

		$this->form_validation->set_rules( 'login', 'login', 'required', array('required' => 'Parameter %s needed.') );
		$this->form_validation->set_rules( 'password', 'password', 'required', array('required' => 'Parameter %s needed.') );

		if ( $this->form_validation->run() == FALSE ) {
			show_api_error(400, $this->form_validation->error_array());
			return;

		} else {

			$this->load->model('mod_user');
			$user = $this->mod_user->auth($login, $password);

			if( !$user ) {
				show_api_error(401, array('Unauthorized, wrong credential'));
				return;
			}

			$this->load->helper('jwt_helper');

			$real_data = json_encode( array(  
				'login' 	=> $login,
				'passwd' 	=> $password,
				'exp'		=> time() + 60*120,
			));

			$token_data = json_encode( array( 
				'_id' => str_replace("=", "", base64_encode(sha1(md5(rand(1111,9999))))) . '.' . jwt_security('encrypt', $real_data)
			));

			$token = jwt::encode( $token_data, $this->config->item('api_encryption_key') );
			show_api_data(200, array('access_token' => $token), array('OK'));
		}
	}
}