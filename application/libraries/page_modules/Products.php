<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products {

    private $CI;
    private $page;
    private $module_name = 'products';

    function __construct($params) {
        $this->CI = & get_instance();
        $this->page = $params['page'];
        $this->init();
    }

    function init() {
        $this->CI->load->model('Pagemodel');
        $this->CI->load->model('product/Productmodel');
		
		$products = array();
		$products = $this->CI->Productmodel->getPageProduct($this->page);
		
		$module_name = "module_".$this->module_name;

		$this->CI->$module_name = $products;
    }



}

?>