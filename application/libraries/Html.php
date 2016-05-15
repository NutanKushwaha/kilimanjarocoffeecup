<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Html {

    private $h_meta = array();

    function __construct() {
        log_message('debug', "HTML Class Initialized");
    }

    function menu($params) {
        $CI = & get_instance();
        return $CI->menumodel->menu($params);
    }

    function addMeta($resource) {
        $this->h_meta[] = $resource;
    }
	
  	function getMeta() {
  		$CI = & get_instance();
  		$file_name = $CI->router->class . '_' . $CI->router->method;
  		$file_path = PATH_CURRENT_MODULE . "/views/meta/$file_name.php";
  		if (file_exists($file_path)) {
  			$CI->html->addMeta($CI->load->view("meta/" . $file_name, array(), TRUE));
  		}
  		
      foreach ($this->h_meta as $row) {
          echo $row . "\r\n";
      }
    }
}