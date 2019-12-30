<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['num_links']             = 3;
$config['use_page_numbers']      = TRUE;
$config['reuse_query_string']    = TRUE;
$config['use_global_url_suffix'] = TRUE;
$config['attributes']            = array('class' => 'page-link');
$config['full_tag_open']         = '<ul class="pagination pagination-primary pagination-circle mg-b-0">';
$config['full_tag_close']        = '</ul>';
$config['first_tag_open']        = '<li class="page-item">';
$config['first_link']            = '<i class="fa fa-angle-double-left"></i>';
$config['first_tag_close']       = '</li>';
$config['prev_link']             = '<i class="fa fa-angle-left"></i>';
$config['prev_tag_open']         = '<li class="page-item">';
$config['prev_tag_close']        = '</li>';
$config['next_link']             = '<i class="fa fa-angle-right"></i>';
$config['next_tag_open']         = '<li class="page-item">';
$config['next_tag_close']        = '</li>';
$config['last_tag_open']         = '<li class="page-item">';
$config['last_link']             = '<i class="fa fa-angle-double-right"></i>';
$config['last_tag_close']        = '</li>';
$config['cur_tag_open']          = '<li class="page-item active"><a class="page-link">';
$config['cur_tag_close']         = '</a></li>';
$config['num_tag_open']          = '<li class="page-item">';
$config['num_tag_close']         = '</li>';
