<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Core {

    var $GET = array();
    var $native_modules = array();
    var $native_plugins = array();

    function __construct() {
        $this->_initialize_core();
    }

    function _initialize_core() {
        $CI = & get_instance();

        define('PATH_MODULES', realpath(APPPATH . '/modules/'));
        define('PATH_CURRENT_MODULE', realpath(APPPATH . '/views/' . $CI->router->directory . '../'));
        define('PATH_PLUGINS', APPPATH . 'plugins/');
        define('PATH_EXTENSIONS', APPPATH . 'extensions/');
        define('PATH_THEMES', APPPATH . 'views/themes/');
        define('THEME', 'default');
        define('CMS_USE_PAGE_URI', TRUE);

        // Load DB and set DB preferences
        $CI->load->database();
        $CI->db->db_debug = TRUE;

        $CI->load->library('session');
        $CI->load->library('user_agent');
        
        $CI->load->library('utility');
        //$CI->load->library('customerauth');
        $CI->load->library('cart');
        $CI->load->library('dwsforms');
        $CI->load->library('memberauth');

        //$CI->load->model('menumodel');

        $CI->load->helper('url');
        $CI->load->helper('text');
        $CI->load->helper('image');
        $CI->load->helper('cms');
        $CI->load->helper('form');
        $CI->load->helper('widget');


        $CI->config->load('custom-config');

        $CI->load->library('http');
        $CI->load->library('assets/assets');
        $CI->load->library('html');

        $CI->load->vars(array('CI' => $CI));
		
        global $MCC_USERNAME;
//        $CI->db->where('company_url_alias', $MCC_USERNAME);
//        $rs = $CI->db->get('company');
//		if($rs && $rs->num_rows() == 1) {
//			$CI->COMPANY = $rs->row_array();		
//			$CI->load->vars(array('COMPANY' => $CI->COMPANY));
//		}else {
//			$CI->COMPANY =false;		
//			$CI->load->vars(array('COMPANY' => false));
//		}
		$CI->load->library('globaldata');

        /*$file_name = $CI->router->class . '_' . $CI->router->method;
        $file_path = PATH_CURRENT_MODULE . "/views/headers/$file_name.php";
        if (file_exists($file_path)) {
            $CI->assets->loadFromFile("headers/" . $file_name);
        }*/
    }

}

?>