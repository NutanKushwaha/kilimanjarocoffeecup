<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Products {

	private $CI;
	private $page;
	private $module_name = 'Products';
	private $module_alias = 'Products';

	function __construct($params) {
		$this->CI = & get_instance();
		$this->page = $params['page'];
		//$this->init();
	}

	function getName() {
		return $this->module_name;
	}

	function getAlias() {
		return $this->module_alias;
	}

	function addView() {
		return "add";
	}

	//function to edit record
	function editView() {
		$this->CI->load->library('form_validation');
		$this->CI->load->helper('form');
		$this->CI->load->model('Pagemodel');

		$inner = array();
		$inner['page'] = $this->page;
		return $this->CI->load->view('page_modules/products/edit', $inner, true);
	}

	function actionAdd() {
		echo "add";
	}

	function actionUpdate() {
		$this->CI->load->library('form_validation');
		$this->CI->load->helper('form');
		$this->CI->load->model('Pagemodel');

		$inner = array();
		$inner['page'] = $this->page;
		return $this->CI->load->view('page_modules/products/edit', $inner, true);
	}

	function actionDelete() {
		$this->CI->load->model('Pagemodel');
		$this->CI->load->model('products/Productsmodel');
		$page = $this->page;

		$product_details = array();
		$product_details = $this->CI->Productsmodel->listAll($page['page_id']);
		if (!empty($product_details)) {
			foreach ($product_details as $product) {
				//delete the previos data if any
				$this->CI->db->where('product_id', $product['product_id']);
				$this->CI->db->delete('product');
			}
		}
	}

}

?>