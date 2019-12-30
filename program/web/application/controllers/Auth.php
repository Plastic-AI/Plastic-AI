<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model('mod_app');
		$this->load->model('mod_user');
		$this->load->model('mod_user_group');
		$this->load->model('mod_upload');
		$this->load->library('google');
	}

	public function index() {
		redirect('login');
	}

	public function forgot_password() {
		$is_logged = $this->session->userdata('is_logged');

		if ($is_logged) {
			redirect('dashboard');
		}

		$email    = $this->input->post('email');
		$captcha  = $this->input->post('captcha');

		$this->form_validation->set_rules('email', 'email', 'trim|required');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|login_captcha_validate');

		if (!$this->form_validation->run()) {
			$length        = 4;
			$font_path     = FCPATH . '/assets/font/Dali-Regular.ttf';
			$captcha_image = $this->generate_login_captcha($length, $font_path);

			$this->session->set_userdata('login_captcha', $captcha_image['code']);
			$this->load->view('layouts/forgot-password', array('captcha_image' => $captcha_image['image']));
		} else {
			$user = $this->mod_user->forgot_password($email);
			if (!$user) {
				$this->session->set_flashdata('error', 'Invalid Email Address');
				redirect('forgot_password');
			}

			$this->session->set_flashdata('success', 'Check Your Email');
			redirect('forgot_password');
		}
	}

	public function reset_password($token = false) {
		if(!$token) {
			redirect('login');
		}

		$is_logged = $this->session->userdata('is_logged');

		if ($is_logged) {
			redirect('dashboard');
		}

		$user = $this->mod_user->get_row(array('forgot_password_token' => $token));
		if (!$user) {
			$this->session->set_flashdata('error', 'Invalid Reset Password Token');
			redirect('login');
		}

		$password			= $this->input->post('password');
		$confirm_password	= $this->input->post('confirm_password');
		$captcha  			= $this->input->post('captcha');

		$this->form_validation->set_rules('password', 'New Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('confirm_password', 'Confirm New Password', 'trim|required|matches[password]');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|login_captcha_validate');

		if (!$this->form_validation->run()) {
			$length        = 4;
			$font_path     = FCPATH . '/assets/font/Dali-Regular.ttf';
			$captcha_image = $this->generate_login_captcha($length, $font_path);

			$this->session->set_userdata('login_captcha', $captcha_image['code']);
			$this->load->view('layouts/reset-password', array('captcha_image' => $captcha_image['image']));
		} else {
			$this->mod_user->update_password($user->user_id, $password);
			$this->session->set_flashdata('success', 'Password Has Been Changed');
			redirect('login');
		}
	}
	
	public function register() {
		$is_logged = $this->session->userdata('is_logged');
		if ($is_logged) {
			redirect('dashboard');
		}

		$name    			= $this->input->post('name', TRUE);
		$login    			= $this->input->post('login', TRUE);
		$email_address    	= $this->input->post('email', TRUE);
		$password 			= $this->input->post('passwd', TRUE);
		$confirm_password 	= $this->input->post('confirm_passwd', TRUE);
		$captcha  			= $this->input->post('captcha', TRUE);

		$this->form_validation->set_rules('name', 'Fullname', 'trim|required|name_format|min_length[1]|max_length[150]');
		$this->form_validation->set_rules('login', 'Username', 'trim|required|login_format|min_length[1]|max_length[32]|add_is_unique[users.user_login]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|min_length[13]|max_length[32]|add_is_unique[users.user_email]');
		$this->form_validation->set_rules('passwd', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('confirm_passwd', 'Confirm Password', 'trim|required|min_length[6]|matches[passwd]');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|login_captcha_validate');

		if (!$this->form_validation->run()) {
			$length        = 4;
			$font_path     = FCPATH . '/assets/font/Dali-Regular.ttf';
			$captcha_image = $this->generate_login_captcha($length, $font_path);
			$this->session->set_userdata('login_captcha', $captcha_image['code']);
			$this->load->view('layouts/register', array('captcha_image' => $captcha_image['image']));
		} else {
			$user = $this->mod_user->get_row(array('user_login' => $login));
			if ($user) {
				$this->session->set_flashdata('error', 'Username or Email already registered');
				redirect('register');
			}

			$group = $this->mod_user_group->get_row(array('user_group_default' => IS_DEFAULT_GROUP_ID));
			$arr_fields = array(
				'user_name'      	=> $name,
				'user_login'     	=> strtolower($login),
				'user_email'     	=> strtolower($email_address),
				'user_password'  	=> sha1($password),
				'user_group_id'  	=> $group->user_group_id,
				'user_status'    	=> STATUS_ENABLE,
				'api_access_only' 	=> STATUS_DISABLE
			);
			
			$insert = $this->mod_user->insert($arr_fields, strtolower($login));
			if($insert) {
				$this->session->set_flashdata('success', 'You has been registered');
				redirect('login');
			} else {
				$this->session->set_flashdata('error', 'Query failed');
				redirect('register');
			}
		}
	}

	public function login() {
		$is_logged = $this->session->userdata('is_logged');

		if ($is_logged) {
			redirect('dashboard');
		}

		$login    = $this->input->post('login');
		$password = $this->input->post('passwd');
		$captcha  = $this->input->post('captcha');

		$this->form_validation->set_rules('login', 'Username', 'trim|required');
		$this->form_validation->set_rules('passwd', 'Password', 'required');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|login_captcha_validate');

		if (!$this->form_validation->run()) {
			$length        = 4;
			$font_path     = FCPATH . '/assets/font/Dali-Regular.ttf';
			$captcha_image = $this->generate_login_captcha($length, $font_path);

			$this->session->set_userdata('login_captcha', $captcha_image['code']);
			$this->load->view('layouts/login', array('captcha_image' => $captcha_image['image'], 'google_login_url' => $this->google->get_login_url()));
		} else {
			$user = $this->mod_user->auth($login, $password);
			if (!$user) {
				$this->session->set_flashdata('error', 'Invalid Login or Password');
				redirect('login');
			}

			$session = array(
				'user_login'    => $user->user_login,
				'user_group_id' => $user->user_group_id
			);
			$this->session->set_userdata('tmp_login', $session);

			if (empty($user->user_secret)) {
				redirect('step2_setup');
			}

			redirect('step2');
		}
	}

	public function step2() {
		$tmp_login = $this->session->userdata('tmp_login');
		if (!$tmp_login) {
			redirect('logout');
		}

		$where = array(
			'user_login'    => $tmp_login['user_login'],
			'user_group_id' => $tmp_login['user_group_id']
		);

		$user = $this->mod_user->get_row($where);
		if (!$user) {
			redirect('logout');
		}

		if (empty($user->user_secret)) {
			redirect('logout');
		}

		$this->form_validation->set_rules('code', 'Authenticator Code', 'trim|required|numeric|exact_length[6]');

		if (!$this->form_validation->run()) {
			$this->load->view('layouts/login-step2');
		} else {
			$this->load->library('authenticator');
			$_code                = $this->input->post('code');
			$verify_authenticator = $this->authenticator->verifyCode($user->user_secret, $_code, 4);
			if (!$verify_authenticator) {
				$this->session->set_flashdata('error', 'Invalid authenticator code!');
				redirect('step2');
			}
			$tmp_login['is_logged'] = true;
			$this->session->set_userdata($tmp_login);
			$this->session->unset_userdata('tmp_login');
			redirect('dashboard');
		}
	}

	public function step2_setup() {
		$tmp_login = $this->session->userdata('tmp_login');
		if (!$tmp_login) {
			redirect('logout');
		}

		$where = array(
			'user_login'    => $tmp_login['user_login'],
			'user_group_id' => $tmp_login['user_group_id']
		);

		$user = $this->mod_user->get_row($where);
		if (!$user) {
			redirect('logout');
		}

		if (!empty($user->user_secret)) {
			redirect('step2');
		}

		$this->load->library('authenticator');

		$this->form_validation->set_rules('code', 'Authenticator Code', 'trim|required|numeric|exact_length[6]');

		if (!$this->form_validation->run()) {
			$url_parts = parse_url(current_url());
			$domain    = str_replace('www.', '', $url_parts['host']);

			$secret = $this->authenticator->createSecret();
			$this->session->set_userdata('setup_secret_code', $secret);

			$view = array(
				'user_login' => $user->user_login,
				'img_qr_code' => $this->authenticator->getQRCodeGoogleUrl($user->user_login, $secret, $domain, array('width' => 250, 'height' => 250))
			);
			$this->load->view('layouts/login-step2-setup', $view);
		} else {
			$_code  = $this->input->post('code');
			$secret = $this->session->userdata('setup_secret_code');

			if (empty($secret)) {
				redirect('step2_setup');
			}

			$verify_authenticator = $this->authenticator->verifyCode($secret, $_code, 4);
			if (!$verify_authenticator) {
				$this->session->set_flashdata('error', 'Invalid authenticator code!');
				redirect('step2_setup');
			}

			$update = $this->mod_user->update_secret($user->user_id, $secret);

			if (!$update) {
				$this->session->set_flashdata('error', 'Update Failed');
				redirect('step2_setup');
			}
			$this->session->unset_userdata('setup_secret_code');

			redirect('step2');
		}
	}

	public function oauth2callback(){
		$google_data = $this->google->validate();
		
		if (!$google_data['email']) {
			$this->session->set_flashdata('error', 'Invalid Google Email Login');
			redirect('login');
		}

		$user_email = $this->mod_user->email($google_data['email']);

		if (!$user_email) {
			$this->session->set_flashdata('error', 'Invalid Google Email Login');
			redirect('login');
		}

		$session_data = array(
						'name'			=> $google_data['name'],
						'email'			=> $google_data['email'],
						'source'		=> 'google',
						'profile_pic'	=> $google_data['profile_pic'],
						'link'			=> $google_data['link'],
						'user_login'    => $user_email->user_login,
						'user_group_id' => $user_email->user_group_id,
						'is_logged'		=> true
					);
		$this->session->set_userdata($session_data);
		redirect('dashboard');
	}

	public function logout() {
		$this->session->sess_destroy();
		unset($_SESSION['access_token']);
		redirect('login');
	}

	public function captcha() {
		$length      = 4;
		$font_path   = FCPATH . '/assets/font/Dali-Regular.ttf';
		$arr_captcha = $this->generate_login_captcha($length, $font_path);
		if (!$arr_captcha) {
			return json_encode(array('status' => '500'));
		}
		$this->session->set_userdata('login_captcha', $arr_captcha['code']);

		$result = array(
			'status' => '200',
			'data'   => $arr_captcha['image']
		);
		header('Content-Type: application/json');
		print json_encode($result);
	}

	private function generate_login_captcha($length = 4, $font_path = '') {
		$possible = '01928576';
		$code     = '';
		for ($i = 0; $i < $length; $i++) {
			$code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
		}

		$font      = $font_path;
		$width     = 200;
		$height    = 60;
		$font_size = $height * 0.75; /* font size will be 75% of the image height */
		$image     = @imagecreate($width, $height);
		if (!$image) {
			return FALSE;
		}

		$background_color = imagecolorallocate($image, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255)); /* set the colours */
		$text_color       = imagecolorallocate($image, mt_rand(30, 50), mt_rand(30, 60), mt_rand(100, 190)); /* set the colours */
		$dot_color        = imagecolorallocate($image, mt_rand(1, 190), mt_rand(1, 190), mt_rand(1, 190)); /* set the colours */
		$noise_color1     = imagecolorallocate($image, 100, 120, 180); /* set the colours */
		$noise_color2     = imagecolorallocate($image, mt_rand(1, 190), mt_rand(1, 190), mt_rand(1, 190)); /* set the colours */

		for ($i = 0; $i < ($width * $height) / 20; $i++) {
			/* generate random dots in background */
			imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $dot_color);
		}

		for ($i = 0; $i < ($width * $height) / 700; $i++) {
			/* generate random lines in background */
			imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $noise_color1);
		}

		for ($i = 0; $i < ($width * $height) / 700; $i++) {
			/* generate random lines in background */
			imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $noise_color2);
		}

		$textbox = imagettfbbox($font_size, 0, $font, $code);
		if (!$textbox) {
			return FALSE;
		}

		#$x = ($width - $textbox[4]) / 2;
		#$y = ($height - $textbox[5]) / 2;
		($font_size > 35) && $font_size = 35;

		$font_size = mt_rand($font_size - 2, $font_size + 2);

		$x = mt_rand(0, $width / ($length * 1.8));
		$y = mt_rand($height - 10, $height);
		#$y = $font_size + 2;

		if (!imagettftext($image, $font_size, 0, $x, $y, $text_color, $font, $code)) {
			return FALSE;
		}

		ob_start();
		imagejpeg($image);
		$image_content = ob_get_contents();
		ob_end_clean();
		imagedestroy($image);

		return array(
			'code'  => $code,
			'image' => 'data:image/png;base64, ' . base64_encode($image_content)
		);
	}
}