<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Globaldata {

	var $CI;
	var $GET = array();

	function __construct() {
		$this->CI = & get_instance();
		log_message('debug', "Metautils Class Initialized");
		$this->load_data();
		$this->load_common_data();
	}

	function load_data() {
		date_default_timezone_set ("Europe/London" );
		$this->CI->load->model('cms/Pagemodel');

		//Compiled blocks
		$global_blocks_rs = $this->CI->Pagemodel->getGlobalBlocks();
		$global_blocks = array();

		foreach ($global_blocks_rs as $block) {
			$block['block_image_url'] = $this->CI->config->item('BLOCK_URL') . $block['block_image'];
			$block['block_image_path'] = $this->CI->config->item('BLOCK_PATH') . $block['block_image'];

			$file_name = 'global_' . $block['block_alias'] . '.php';
			if (file_exists("application/views/themes/" . THEME . "/blocks/" . $file_name)) {
				$compiled_tpl = $this->CI->load->view("themes/" . THEME . "/blocks/$file_name", $block, true);
			} else {
				$compiled_tpl = $this->CI->load->view("themes/" . THEME . "/blocks/default" . ".php", $block, true);
			}
			$global_blocks["global_" . $block['block_alias']] = $compiled_tpl;
		}
		$this->CI->load->vars($global_blocks);

		//Informational Messages
		$info = '';
		$info_key = $this->CI->session->flashdata('INFO');
		if ($info_key) {
			$this->CI->lang->load('info', 'english');
			$info = $this->CI->lang->line($info_key);
			$this->CI->load->vars(array('INFO' => $info));
		}

		//Error Messages
		$error = '';
		$error_key = $this->CI->session->flashdata('ERROR');
		if ($error_key) {
			$this->CI->lang->load('error', 'english');
			$error = $this->CI->lang->line($error_key);
			$this->CI->load->vars(array('ERROR' => $error));
		}

		//Success Messages
		$success = '';
		$success_key = $this->CI->session->flashdata('SUCCESS');
		if ($success_key) {
			$this->CI->lang->load('success', 'english');
			$success = $this->CI->lang->line($success_key);
			$this->CI->load->vars(array('SUCCESS' => $success));
		}

		//Pseudo GET Array
		$query_string = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		if ($query_string && !is_null($query_string)) {
			$GET = array();
			parse_str($query_string, $GET);
			$this->GET = $GET;
		}

		//Get config settings
		$rs = $this->CI->db->get('config');
		foreach ($rs->result_array() as $row) {
			define('MCC_' . $row['config_key'], $row['config_value']);
		}
		
	}
	private function load_common_data() {	 
		date_default_timezone_set ("Europe/London" );		
		define('FRONT', 'Front User');
		define('GUEST', 'Front User');

		$mccIps = [];
		$mccIps[] = '127.0.0.1';
		$mccIps[] = '203.134.215.73';
		$mccIps[] = '202.164.47.162';
		$mccIps[] = '202.164.47.164';
		if( in_array($_SERVER['REMOTE_ADDR'], $mccIps ) ) {
			define('MCCDEV', TRUE);
		}else{
			define('MCCDEV', FALSE);
		}
	}
}
