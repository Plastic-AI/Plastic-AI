<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_upload extends Titan_Model {
	public function __construct() {
		parent::__construct();

        $this->load->library('curl');
	}
	
	public function image_url($name, $url, $path) {
		$filename = $name . '.jpg';
		$contents = $this->curl->get($url);
		$name_from_url = substr($url, strrpos($url, '/') + 1);
		if(preg_match('/'.ALLOWED_TYPES.'/i', $name_from_url)) {
			file_put_contents($path . $filename, $contents);
			return array('status' => true, 'filename' => $filename);
		} else {
			return array('status' => false, 'error' => 'Not supported image format.');
		}
	}
	
	public function image_base64($name, $base64_string, $path) {
		$data = explode(',', $base64_string);
		$ext = "";
		switch ($data[0]) {
			case "data:image/png;base64";
				$ext = "png";
				break;
			case "data:image/jpg;base64";
				$ext = "jpg";
				break;
			case "data:image/jpeg;base64";
				$ext = "jpg";
				break;
			case "data:image/gif;base64";
				$ext = "gif";
				break;
			default:
				$ext = "unknown";
				break;
		}
		$filename = $name . '.' . $ext;
		if(preg_match('/'.ALLOWED_TYPES.'/i', $ext)) {
			file_put_contents($path . $filename, base64_decode($data[1]));
			return array('status' => true, 'filename' => $filename);
		} else {
			return array('status' => false, 'error' => 'Not supported image format.');
		}
	}

	public function image_file($name, $value, $path) {
		$image 		= $_FILES[$value]["name"];
		$exstension = substr($image, strrpos($image, '.')+1);
		$filename	= $name . '.' . $exstension;

		$config['file_name']        = $filename;
		$config['upload_path']      = $path;
		$config['allowed_types']    = ALLOWED_TYPES;
		$config['overwrite']		= true;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ( !$this->upload->do_upload($value) ) {
            $error = array(str_replace(array('<p>', '</p>'), '', $this->upload->display_errors()));
			return array('status' => false, 'error' => array('reason' => $error, 'conf' => $config));
		} else {
			//return array('status' => true, 'filename' => $filename, 'data' => $this->upload->data());
			return array('status' => true, 'filename' => $filename);
		}
    }

}