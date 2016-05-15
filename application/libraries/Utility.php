<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility {

    var $CI;
    var $template;
    function __construct() {
		$this->CI = & get_instance();
		
		log_message('debug', "Utility Class Initialized");
	}

   	function show404() {
		set_status_header('404');

        $inner = array();
		$page = array();
		$page['title'] = 'Page Not Found';
		$page['contents'] = $this->CI->load->view('themes/'.THEME.'/pages/404', $inner, true);
		$template = 'themes/'.THEME.'/templates/subpage';
		$this->CI->load->view($template, $page);
	}

    function accessDenied() {
        $inner = array();
        $page = array();
        $page['title'] = 'Access Denied';
        $page['contents'] = $this->CI->load->view('pages/access-denied', $inner, true);
        $this->CI->load->view('shell', $page);
    }

    function showMessage($title, $message) {
        $inner = array();
        $page = array();
        $inner['title'] = $title;
        $inner['message'] = $message;
        $page['contents'] = $this->CI->load->view('themes/' . MCC_THEME . '/pages/message', $inner, true);
        $this->CI->load->view('themes/' . MCC_THEME . '/shell', $page);
    }

}

?>