<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Req_setting extends Titan_Req_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('mod_setting');
	}

	public function index() {
		$_telegram_api			= $this->input->post('txtTelegramApi', TRUE);
		$_telegram_chatid 		= $this->input->post('txtTelegramChatid', TRUE);
		$_notification_detail	= $this->input->post('txtDetail', TRUE);
		$_notification_status	= $this->input->post('cbStatus', TRUE);

		$this->form_validation->set_rules('txtTelegramApi', 'Telegram API', 'trim|required|trim|max_length[45]');
		$this->form_validation->set_rules('txtTelegramChatid', 'Telegram Chat ID', 'trim|required|trim|max_length[15]');
		$this->form_validation->set_rules('txtDetail', 'Notification Detail', 'trim|required|trim');
		$this->form_validation->set_rules('cbStatus', 'Notification Status', 'trim|required|in_list[' . STATUS_ENABLE . ',' . STATUS_DISABLE . ']');

		if ($this->form_validation->run() == FALSE) {
			$callback['fields'] = $this->form_validation->error_array();
			$this->ajax_output(array(), 400, $callback);
		} else {
            $arr_fields = array(
                'telegram_api'  		=> $_telegram_api,
                'telegram_chatid'     	=> $_telegram_chatid,
                'notification_detail'	=> $_notification_detail,
                'notification_status'	=> $_notification_status
            );

            $update = $this->mod_setting->update($arr_fields, array('created_by' => 'admin'));
            if (!$update) {
                $callback['noty'] = array('type' => 'error', 'text' => 'Query failed');
            } else {
                $callback['noty'] = array('type' => 'success', 'text' => 'Updated');
            }

            $this->ajax_output('', 200, $callback);
		}
	}
}