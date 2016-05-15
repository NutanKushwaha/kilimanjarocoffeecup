<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Metautils {
	var $CI;
	
   function Metautils() {		
		$this->CI =& get_instance();
		log_message('debug', "Metautils Class Initialized");
	}
	
	function getUID() {
		$class_name = $this->CI->router->class;
		if($class_name == '') $class_name = 'welcome';
		
		$method_name = $this->CI->router->method;
		if($method_name == '') $method_name = 'index';
		
		return $class_name."_".$method_name;
	}
	
	function getDirectory() {
		return $this->CI->router->directory;
	}
	
	function getTitle() {
		$this->CI->lang->load('titles', 'english');
		return $this->CI->lang->line($this->getUID());
	}
}
?>