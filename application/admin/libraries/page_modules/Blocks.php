<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class BLocks {

	private $CI;
	private $page;
	private $module_alias = 'Blocks';
	private $module_name = 'Cash For Cars Blocks';

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
		return $this->CI->load->view('page_modules/blocks/edit', $inner, true);
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
		return $this->CI->load->view('page_modules/blocks/edit', $inner, true);
	}

	function actionDelete() {
		$this->CI->load->model('Pagemodel');
		$this->CI->load->model('Blockmodel');
		$page = $this->page;

		$blocks_details = array();
		$blocks_details = $this->CI->Blockmodel->listAll($page['page_id']);

		/* if (!empty($blocks_details)) {
		  foreach ($blocks_details as $block) {

		  $file_name = str_ireplace('/', '_', $block['page_uri']) . '_' . $block['block_alias'] . '.php';
		  if (file_exists("../application/views/themes/" . THEME . "/blocks/" . $file_name)) {
		  @unlink("../application/views/themes/" . THEME . "/blocks/" . $file_name);
		  }
		  $path = $this->config->item('BLOCK_IMAGE_PATH');
		  $filename = $path . $block['block_image'];
		  if (file_exists($filename)) {
		  @unlink($filename);
		  }

		  //delete the previos data if any
		  $this->CI->db->where('product_id', $block['product_id']);
		  $this->CI->db->delete('product');
		  }
		  } */
	}

}

?>