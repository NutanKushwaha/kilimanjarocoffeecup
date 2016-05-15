<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Globaldata {
	var $CI;
	
   function __construct() {		
		$this->CI =& get_instance();
		if ( !$this->CI->input->is_cli_request() ){		
			log_message('debug', "Metautils Class Initialized");
			$this->load_data();
			$this->load_common_data();
		}
	}
	
	private function load_data() {
		
		//Informational Messages
		$info = '';
		$info_key = $this->CI->session->flashdata('INFO');
		if($info_key) {
			$this->CI->lang->load('info', 'english');
			$info = $this->CI->lang->line($info_key);
			$this->CI->load->vars(array('INFO' => $info));
		}
		
		//Error Messages
		$error = '';
		$error_key = $this->CI->session->flashdata('ERROR');
		if($error_key) {
			$this->CI->lang->load('error', 'english');
			$error = $this->CI->lang->line($error_key);
			$this->CI->load->vars(array('ERROR' => $error));
		}
		
		//Success Messages
		$success = '';
		$success_key = $this->CI->session->flashdata('SUCCESS');
		if($success_key) {
			$this->CI->lang->load('success', 'english');
			$success = $this->CI->lang->line($success_key);
			$this->CI->load->vars(array('SUCCESS' => $success));
		}
				
		//Get config settings
		$rs = $this->CI->db->get('config');
		$this->CI->db->reset_query();		
		foreach($rs->result_array() as $row) {
			define('MCC_'.$row['config_key'], $row['config_value']);
		}
		
		//Fetch partner type
		/*$this->CI->load->model('cms/Partnermodel');
		$partner_type = array();
		$partner_type = $this->CI->Partnermodel->listAll();
		foreach ($rs as $row) {
			$partner_type[$row['partner_type_id']] = $row['partner_type'];
		}
		$this->CI->load->vars(array('PARTNERTYPE' => $partner_type));*/
		
		//Pseudo GET Array
		$query_string = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		if ($query_string && !is_null($query_string)) {
			$GET = array();
			parse_str($query_string, $GET);
			$this->CI->GET = $GET;
		}
	}
	
	private function load_common_data() {	 
		date_default_timezone_set ("Europe/London" );
		define('ADMIN', 'Super Admin');
		define('CMP_ADMIN', 'Company');
		define('USER', 'Company Customer');
		define('FRONT', 'Front User');
		define('GUEST', 'Guest User');
		define('CMP_MD', 'Director');
		define('CMP_PM', 'Purchase Manager');

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
