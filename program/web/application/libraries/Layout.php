<?php
class Layout{

	private $CI;

	public function __construct() {
		$this->CI 	=& get_instance();
	}

	public function view($data = array(), $layout = 'layouts/frame') {
		$this->CI->load->view( $layout, $data);
	}
}