<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_app extends Titan_Model {
	public function __construct() {
        parent::__construct();

        $this->load->library('curl');
    }

	private function image_base64($path = false) {
		$type 	= pathinfo($path, PATHINFO_EXTENSION);
		$data 	= file_get_contents($path);
		$image	= 'data:image/' . $type . ';base64,' . base64_encode($data);

		return $image;
	}

	public function detect_plastic($image = false) {
        if(is_file($image)) {
        	$command = 'python3 brain/predict.py --image ' . $image . ' --model brain/model/plastic.model --label-bin brain/model/plastic_lb.pickle --width 32 --height 32 --flatten 1';        	
            $req = shell_exec($command);
            return $req;
        } else {
            return false;
        };
	}
}