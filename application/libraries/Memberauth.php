<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Memberauth {

	private $CI;
	private $member_data;
	private $login_flag = FALSE;

	function CI_Memberauth() {
		$this->CI =& get_instance();
		$this->LOGIN_DATA = array();
		log_message('debug', "Memberauth Class Initialized");

		$this->login_flag = $this->validSession();
	}

	private function validSession() {
		$userid = $this->CI->session->userdata('CUSTOMER_ID');

		if(isset($userid) && (trim($userid) != '') && is_numeric($userid)) {
			$this->CI->load->model('customer/Customermodel');
			$user = $this->CI->Customermodel->fetchByID($userid);
            //print_r ($user); exit();
			if($user)  {
				$this->member_data = $user;
				return TRUE;
			}
			$this->CI->session->sess_destroy();
		}

		return FALSE;
	}

	function checkAuth() {
		if($this->login_flag) return $this->member_data;

		return false;
	}

}
?>
