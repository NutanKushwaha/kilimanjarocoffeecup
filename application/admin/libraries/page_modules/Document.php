<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Document {

	private $CI;
	private $page;
	private $module_name = 'Pdf';
	private $module_alias = 'Pdf';

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
		return $this->CI->load->view('page_modules/document/edit', $inner, true);
	}

	function actionAdd() {
		echo "add";
	}

	function actionUpdate() {
		$this->CI->load->library('form_validation');
		$this->CI->load->helper('form');
		$this->CI->load->model('Pagemodel');

		$inner = array();
		return $this->CI->load->view('page_modules/document/edit', $inner, true);
	}

}

?>