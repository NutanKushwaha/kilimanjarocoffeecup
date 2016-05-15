<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Youtube {

	private $CI;
	private $page;
	private $module_name = 'Youtube';
	private $module_alias = 'Youtube';

	function __construct($params) {
		$this->CI = & get_instance();
		$this->page = $params['page'];
		$this->init();
	}

	function init() {
		$this->CI->load->library('form_validation');
		$this->CI->form_validation->set_rules('youtube_url', 'Youtube Url', 'trim');
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
		$this->CI->load->model('slideshow/Slideshowmodel');

		$current_url = array();
		$current_url = $this->CI->Pagemodel->getPageData($this->page, 'youtube');

		$show_url = array();
		$show_url = $this->CI->Pagemodel->getPageData($this->page, 'show_url');

		$inner = array();
		$inner['page'] = $this->page;
		$inner['current_url'] = $current_url;
		$inner['show_url'] = $show_url;

		return $this->CI->load->view('page_modules/youtube/edit', $inner, true);
	}

	function actionAdd() {
		echo "add";
	}

	function actionDelete() {
		$this->CI->load->model('Pagemodel');
		$page = $this->page;

		$slideshow = array();
		$slideshow = $this->CI->Pagemodel->getPageData($this->page, 'youtube');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $slideshow['page_data_id']);
		$this->CI->db->delete('page_data');

		/* $show_url = array();
		  $show_url = $this->CI->Pagemodel->getPageData($this->page, 'show_url');

		  //delete the previos data if any
		  $this->CI->db->where('page_data_id', $show_url['page_data_id']);
		  $this->CI->db->delete('page_data'); */
	}

	function actionUpdate() {
		$this->CI->load->model('Pagemodel');
		$page = $this->page;

		$slideshow = array();
		$slideshow = $this->CI->Pagemodel->getPageData($this->page, 'youtube');

		//delete the previos data if any
		$this->CI->db->where('page_data_id', $slideshow['page_data_id']);
		$this->CI->db->delete('page_data');

		//insert new data
		$data = array();
		$data['page_setting'] = 'youtube';
		$data['module_name'] = $this->getAlias();
		$data['page_setting_value'] = $this->CI->input->post('youtube_url', true);
		$data['page_id'] = $page['page_id'];
		$this->CI->db->insert('page_data', $data);

		/* $show_url = array();
		  $show_url = $this->CI->Pagemodel->getPageData($this->page, 'show_url');

		  //delete the previos data if any
		  $this->CI->db->where('page_data_id', $show_url['page_data_id']);
		  $this->CI->db->delete('page_data');

		  //insert new data
		  $data = array();
		  $data['page_setting'] = 'show_url';
		  $data['module_name'] = $this->getAlias();
		  $data['page_setting_value'] = $this->CI->input->post('show_url', true);
		  $data['page_id'] = $page['page_id'];
		  $this->CI->db->insert('page_data', $data); */
	}

}

?>