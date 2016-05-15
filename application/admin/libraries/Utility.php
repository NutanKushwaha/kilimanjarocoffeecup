<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Utility {
	var $CI;	
   function __construct() {
		$this->CI = & get_instance();
		
		log_message('debug', "Utility Class Initialized");
	}
	
	function show404( $opts = []) {
		$top_text	= 'Page not found';
		$bottom_text= 'Page does not exist';
		extract( $opts );		
		set_status_header('404');
        $inner = array();
		$page = array();
		$inner['top_text'] = $top_text;
		$inner['bottom_text'] = $bottom_text;		
		$page['content'] = $this->CI->load->view(THEME.'pages/404', $inner, true);
		$template = $this->CI->load->_ci_cached_vars['CI']->template['without_menu'];
		$this->CI->load->view($template, $page);
	}
	
	function accessDenied() {
		$inner = array();
		$page = array();
		$page['title'] = 'Access Denied';
		$page['content'] = $this->CI->load->view(THEME.'pages/access-denied', $inner, true);
		$template = $this->CI->load->_ci_cached_vars['CI']->template['without_menu'];
		$this->CI->load->view($template, $page);
	}
	
}
