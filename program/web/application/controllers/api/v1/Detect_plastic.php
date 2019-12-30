<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detect_plastic extends Titan_Api_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('mod_api');
		$this->load->model('mod_app');
		$this->load->model('mod_user');
		$this->load->model('mod_upload');
		$this->load->model('mod_checking_log');
	}

	public function index() {
		$this->mod_api->method('POST');

		$arr_user = $this->mod_user->get_row(array('user_login' => $this->user_login));

		$image_url 	    = $this->input->post('image_url');
		$image_base64	= $this->input->post('image_base64');

		$this->form_validation->set_rules( 'image_url', 'image_url1', 'trim' );
		$this->form_validation->set_rules( 'image_base64', 'image_base64', 'trim' );

		if ( $this->form_validation->run() == FALSE ) {
			show_api_error(400, $this->form_validation->error_array());
			return;
		}

		$path = IMG_UPLOAD_PATH.$arr_user->user_id.'/';

		if(!file_exists($path)) {
			mkdir($path, 0755, true);
		}

		if(!is_dir($path)) {
			show_api_error(500, array('Failed to creat image upload directory.'));
			return;
		}

		// Upload Image
		if(!empty($image_url)) {
			$upload = $this->mod_upload->image_url($arr_user->user_id . '_' . time() . '_file', $image_url, $path);
			if($upload['status'] == false) {
				show_api_error(500, array($upload['error']));
				return;
			}

			$image = $upload['filename'];
		} elseif(!empty($image_base64)) {
			$upload = $this->mod_upload->image_base64($arr_user->user_id . '_' . time() . '_file', $image_base64, $path);
			if($upload['status'] == false) {
				show_api_error(500, array($upload['error']));
				return;
			}

			$image = $upload['filename'];
		} elseif(!empty($_FILES["image_file"]["name"])) {
			$upload = $this->mod_upload->image_file($arr_user->user_id . '_' . time() . '_file', 'image_file', $path);
			if($upload['status'] == false) {
				show_api_error(500, array($upload['error']));
				return;
			}

			$image = $upload['filename'];
		} else {
			$image = NULL;
		}

		if($image != NULL) {
			$res = $this->mod_app->detect_plastic($path.$image);
			if($res) {
				$json = json_decode($res);
			} else {
				show_api_error(400, array('Failed to connect PlasticAI brain. Try again ...'));
				return;
			}
		} else {
			show_api_error(400, array('Failed to upload images.'));
			return;
		}

		$arr_collects = array(
			'user_id'				=> $arr_user->user_id,
			'plastic_type'			=> $json->type,
			'plastic_percentage'	=> $json->percentage,
			'plastic_file'			=> $json->file,
			'time_used'				=> $json->time_used ? $json->time_used : NULL,
			'request_id'			=> $json->_id,
			'request_data'			=> json_encode($json, true),
			'plastic_status'		=> STATUS_ENABLE
		);

		$insert = $this->mod_checking_log->insert($arr_collects);
		if(!$insert) {
			show_api_error(500, array('Query failed.'));
			return;
		}

		$data = array(
			'request_id'	=> $json->_id,
			'type'			=> $json->type,
			'percentage'	=> $json->percentage,
			'file'			=> base_url($json->file),
			'time_used'		=> $json->time_used ? $json->time_used : NULL,
			'status'		=> STATUS_ENABLE
		);

		show_api_data(200, $data, array('OK'));
		return;
	}
}