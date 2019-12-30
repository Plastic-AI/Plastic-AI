<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dashboard'] = array(
	'name'    => 'Dashboard',
	'methods' => array(
		array(
			'name' => 'SUMMARY',
			'key'  => 'index'
		)
	)
);

$config['change_logs'] = array(
	'name'    => 'Change Logs',
	'methods' => array(
		array(
			'name' => 'SUMMARY',
			'key'  => 'index'
		)
	)
);

$config['setting'] = array(
	'name'    => 'Setting',
	'methods' => array(
		array(
			'name' => 'LIST',
			'key'  => 'index'
		)
	)
);

$config['detect_plastic'] = array(
	'name'    => 'Detect Plastic',
	'methods' => array(
		array(
			'name' => 'DETECT',
			'key'  => 'index'
		)
	)
);

$config['apidocs'] = array(
	'name'    => 'API',
	'methods' => array(
		array(
			'name' => 'DOCUMENTATION',
			'key'  => 'index'
		)
	)
);

$config['detection_logs'] = array(
	'name'    => 'Detection Log',
	'methods' => array(
		array(
			'name' => 'LIST', 
			'key'  => 'index'
		),
		array(
			'name' => 'DETAIL',
			'key'  => 'detail'
		),
		array(
			'name' => 'DELETE',
			'key'  => 'delete'
		)
	)
);

$config['user_group'] = array(
	'name'    => 'User Group',
	'methods' => array(
		array(
			'name' => 'LIST',
			'key'  => 'index'
		),
		array(
			'name' => 'ADD',
			'key'  => 'add'
		),
		array(
			'name' => 'UPDATE',
			'key'  => 'update'
		)
	)
);

$config['user'] = array(
	'name'    => 'User',
	'methods' => array(
		array(
			'name' => 'LIST',
			'key'  => 'index'
		),
		array(
			'name' => 'ADD',
			'key'  => 'add'
		),
		array(
			'name' => 'UPDATE',
			'key'  => 'update'
		),
		array(
			'name' => 'CHANGE PASSWORD',
			'key'  => 'change_password'
		),
		array(
			'name' => 'RESET AUTHENTICATE',
			'key'  => 'authenticate'
		),
		array(
			'name' => 'TERMINATE',
			'key'  => 'terminate'
		)
	)
);