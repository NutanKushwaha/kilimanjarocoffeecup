<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Casehistories {

	private $page;
	private $module_name = 'Case Studies Category';
	private $module_alias = 'casehistories';

	function __construct($params) {

		$this->page = $params['page'];
		$this->init();
	}

	function init() {
		$CI = & get_instance();
		$CI->load->library('form_validation');
		$CI->form_validation->set_rules('casestudy_category_alias', 'Casestudy Category', 'trim');
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
		$CI = & get_instance();
		$CI->load->library('form_validation');
		$CI->load->helper('form');
		$CI->load->model('Pagemodel');
		$CI->load->model('slideshow/Slideshowmodel');

		$current_category = array();
		$current_category = $CI->Pagemodel->getPageData($this->page, 'casestudy_category_alias');

		//fetch slideshow
		$categories = array();
		$categories[''] = '--Select Category--';
		$rs = $CI->db->get('casestudy_category');
		foreach ($rs->result_array() as $row) {
			$categories[$row['casestudy_category_alias']] = $row['casestudy_category'];
		}

		$inner = array();
		$inner['page'] = $this->page;
		$inner['categories'] = $categories;
		$inner['current_category'] = $current_category;

		//include APPPATH.'/modules/cms/views/page_modules/slideshow/edit.php';
		return $CI->load->view('cms/page_modules/casehistories/edit', $inner, true);
	}

	function actionAdd() {
		echo "add";
	}

	function actionDelete() {
		$CI = & get_instance();
		$CI->load->model('Pagemodel');
		$page = $this->page;

		$slideshow = array();
		$slideshow = $CI->Pagemodel->getPageData($this->page, 'casestudy_category_alias');

		//delete the previos data if any
		$CI->db->where('page_data_id', $slideshow['page_data_id']);
		$CI->db->delete('page_data');
	}

	function actionUpdate() {
		$CI = & get_instance();
		$CI->load->model('Pagemodel');
		$page = $this->page;

		$slideshow = array();
		$slideshow = $CI->Pagemodel->getPageData($this->page, 'casestudy_category_alias');

		//delete the previos data if any
		$CI->db->where('page_data_id', $slideshow['page_data_id']);
		$CI->db->delete('page_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'casestudy_category_alias';
		$data['module_name'] = $this->getAlias();
		$data['page_setting_value'] = $CI->input->post('casestudy_category_alias', true);
		$data['page_id'] = $page['page_id'];
		$CI->db->insert('page_data', $data);
	}

}

?>