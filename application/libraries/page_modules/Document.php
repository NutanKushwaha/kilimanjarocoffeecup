<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Document {

    private $CI;
    private $page;
    private $module_name = 'document';

    function __construct($params) {
        $this->CI = & get_instance();
        $this->page = $params['page'];
        $this->init();
    }

    function init() {
        $this->CI->load->model('Pagemodel');
        $this->CI->load->model('document/Documentmodel');
		$documents = array();
		$documents = $this->CI->Documentmodel->getPageDocument($this->page);

		$module_name = "module_".$this->module_name;

		$this->CI->$module_name = $documents;
    }



}

?>